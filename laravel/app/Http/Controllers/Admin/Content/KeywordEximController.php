<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keyword;

class KeywordEximController extends Controller
{
    public function file()
    {
        $keywords = Keyword::orderBy('id', 'asc')->get();

        $data = '';
        foreach ($keywords as $keyword) {
            $data .= $keyword->keyword . "\n";
        }

        return response($data, 200)->withHeaders([
            'Content-Type' => 'text/plain',
            'Cache-Control' => 'no-store, no-cache',
            'Content-Disposition' => 'attachment; filename="keywords.txt"',
        ]);
    }

    public function import()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Keywords') => route('admin.keywords.index'),
            __('Import Keywords') => '',
        ];

        return view('admin.content.keywords.import', compact('breadcrumb'));
    }

    public function saveImport(Request $request)
    {
        $validated = $request->validate([
            'keywords' => ['required'],
        ]);

        $keywords = $request->keywords;
        $keywords = preg_split('/\r\n|\r|\n/', $keywords);
        
        $insertKeywords = [];
        foreach ($keywords as $keyword) {
            if (!empty($keyword)) {
                Keyword::firstOrCreate(['keyword' => $keyword]);
            }
        }

        return redirect()->route('admin.keywords.import')->with('success', __('Keywords import success!'));
    }
}
