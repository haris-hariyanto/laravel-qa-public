<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Keyword;
use App\Models\Question;
use App\Models\Recommendation;

class LinkContents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Membuat Rekomendasi');

        $loop = true;
        while ($loop) {
            $limit = $this->ask('Jumlah keyword', 10);

            if (is_numeric($limit)) {
                $loop = false;
            }
        }

        $lastUsedKeyword = Keyword::where('is_last', 'Y')->first();
        if (!$lastUsedKeyword) {
            $keywords = Keyword::orderBy('id', 'asc')
                ->take($limit)
                ->get();
        }
        else {
            $keywords = Keyword::where('id', '>', $lastUsedKeyword->id)
                ->take($limit)
                ->get();
        }
        
        foreach ($keywords as $keyword) {
            $this->line('[ * ] Keyword : ' . $keyword->keyword);

            $keyword->update([
                'is_last' => 'Y',
            ]);
            Keyword::where('id', '<>', $keyword->id)->update([
                'is_last' => 'N',
            ]);

            $keywordSplit = explode(' ', $keyword->keyword);

            $questions = Question::where(function ($query) use ($keywordSplit) {
                    foreach ($keywordSplit as $keyword) {
                        $query->where('question', 'like', '%' . $keyword . '%');
                    }
                })
                ->pluck('id');
            $this->line('[ * ] Ditemukan ' . count($questions) . ' konten');

            if (count($questions) <= config('content.related_questions')) {
                $this->line('--------------------');
                continue;
            }

            $questionsToLink = Question::where('have_recommendations', 'N')
                ->where(function ($query) use ($keywordSplit) {
                    foreach ($keywordSplit as $keyword) {
                        $query->where('question', 'like', '%' . $keyword . '%');
                    }
                })
                ->get();

            foreach ($questionsToLink as $question) {
                if ($question->have_recommendations == 'Y') {
                    $this->line('--------------------');
                    continue;
                }

                $recommendations = array_rand(array_flip($questions->toArray()), config('content.related_questions'));

                foreach ($recommendations as $recommendation) {
                    Recommendation::firstOrCreate([
                        'parent_id' => $question->id,
                        'child_id' => $recommendation,
                    ]);
                } // [END] Recommendation loop

                $question->update([
                    'have_recommendations' => 'Y',
                    'recommendation_time' => now(),
                ]);
            } // [END] Question loop

            $this->line('--------------------');
        }
    }
}
