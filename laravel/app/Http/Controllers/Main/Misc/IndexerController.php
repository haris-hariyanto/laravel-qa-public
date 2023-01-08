<?php

namespace App\Http\Controllers\Main\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Indexer;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;

class IndexerController extends Controller
{
    public function index(Request $request)
    {
        $key = $request->query('key');
        $indexerKey = config('app.indexer_key');
        
        if ($key != $indexerKey) {
            abort(403);
        }

        $indexers = Storage::disk('indexer')->files('/');
        if (!$indexers) {
            Indexer::truncate();
            return [
                'success' => false,
                'message' => 'No indexer available.',
            ];
        }

        Indexer::where('id', '>', 0)->update([
            'is_checked' => 'N',
        ]);
        foreach ($indexers as $indexer) {
            Indexer::updateOrCreate(
                ['path' => $indexer],
                ['is_checked' => 'Y']
            );
        }
        Indexer::where('is_checked', 'N')->delete();

        $lastUsedIndexer = Indexer::where('is_last_used', 'Y')->first();
        if ($lastUsedIndexer) {
            $indexer = Indexer::where('id', '>', $lastUsedIndexer->id)->first();
            if (!$indexer) {
                $indexer = Indexer::first();
            }
        }
        else {
            $indexer = Indexer::first();
        }

        $client = new \Google_Client();
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

        $configPath = resource_path('indexer/' . $indexer->path);
        $client->setAuthConfig($configPath);
        $httpClient = $client->authorize();

        $content = Question::where('index_requested', 'N')->first();
        if (!$content) {
            return [
                'success' => false,
                'message' => 'No content available.',
            ];
        }

        $url = route('content.question', ['question' => $content]);
        $data = [
            'url' => $url,
            'type' => 'URL_UPDATED',
        ];
        $data = json_encode($data, JSON_UNESCAPED_SLASHES);

        $response = $httpClient->post($endpoint, ['body' => $data]);
        $statusCode = $response->getStatusCode();

        if ($statusCode != '200') {
            return [
                'success' => false,
                'message' => 'Error ' . $statusCode,
            ];
        }

        $indexer->update(['is_last_used' => 'Y']);
        Indexer::where('id', '<>', $indexer->id)->update([
            'is_last_used' => 'N',
        ]);

        $content->update([
            'index_requested' => 'Y',
        ]);

        return [
            'success' => true,
            'result' => [
                'url' => $url,
                'config' => $indexer->path,
            ],
        ];
    }
}
