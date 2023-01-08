<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Models\ContentCache;
use App\Models\Recommendation;

class CreateCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:cache';

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
        $parallerMode = $this->confirm('Jalankan caching paralel?', false);
        if ($parallerMode) {
            $lastID = $this->ask('Set ID pertama', 0);
        }
        else {
            $lastID = false;

            $lastCachedContent = ContentCache::orderBy('id', 'desc')->first();
            if ($lastCachedContent) {
                $question = Question::where('slug', $lastCachedContent->slug)->first();
                if ($question) {
                    $lastID = $question->id;
                }
                else {
                    $lastID = false;
                }
            }
            else {
                $lastID = false;
            }
        }

        $loop = true;

        while ($loop) {
            if ($lastID) {
                $content = Question::where('id', '>', $lastID)->first();
            }
            else {
                $content = Question::orderBy('id', 'asc')->first();
            }

            if (!$content) {
                break;
            }
            else {
                $lastID = $content->id;
            }

            $this->info('[ * ] Membuat cache : ' . $content->slug);
            $isContentCacheExists = ContentCache::where('slug', $content->slug)->exists();
            if ($isContentCacheExists) {
                $this->info('[ * ] Cache sudah ada');
            }
            else {
                // Data cache
                $cachedData = [];
                $cachedData['id'] = $content->id;
                $cachedData['grade'] = [
                    'id' => $content->grade->id,
                    'slug' => $content->grade->slug,
                    'name' => $content->grade->name,
                ];
                $cachedData['subject'] = [
                    'id' => $content->subject->id,
                    'slug' => $content->subject->slug,
                    'name' => $content->subject->name,
                ];
                $cachedData['answers'] = [];
                foreach ($content->answers as $answer) {
                    $cachedData['answers'][] = [
                        'id' => $answer->id,
                        'answer' => $answer->answer,
                        'is_best' => $answer->is_best,
                        'vote' => $answer->vote,
                    ];
                }

                // Get related questions
                $recommendations = Recommendation::where('parent_id', $content->id)->count();
                if ($recommendations >= config('content.related_questions')) {
                    $recommendations = Recommendation::where('parent_id', $content->id)
                        ->orderBy('id', 'desc')
                        ->take(config('content.related_questions'))
                        ->pluck('child_id')
                        ->toArray();
                    $relatedQuestions = Question::with('answers')->whereIn('id', $recommendations)->get();
                }
                else {
                    $relatedQuestions = Question::with('answers')
                        ->where('subject_id', $content->subject->id)
                        ->where('grade_id', $content->grade->id)
                        ->where('id', '<', $content->id)
                        ->take(config('content.related_questions'))
                        ->orderBy('id', 'desc')
                        ->get();
        
                    $relatedQuestionsCount = count($relatedQuestions);
                    if ($relatedQuestionsCount < config('content.related_questions')) {
                        $relatedQuestions = Question::with('answers')
                            ->where('subject_id', $content->subject->id)
                            ->where('grade_id', $content->grade->id)
                            ->where('id', '>', $content->id)
                            ->take(config('content.related_questions'))
                            ->orderBy('id', 'asc')
                            ->get();
                    }
                }

                $cachedData['relatedQuestions'] = [];
                foreach ($relatedQuestions as $relatedQuestion) {
                    $subContent = [];
                    $subContent['question'] = $relatedQuestion->question;
                    $subContent['answers'] = [];
                    foreach ($relatedQuestion->answers as $answer) {
                        $subContent['answers'][] = $answer->answer;
                    }
                    $cachedData['relatedQuestions'][] = $subContent;
                }
                // [END] Get related questions

                $cachedData = json_encode($cachedData);
                // [END] Data cache

                ContentCache::create([
                    'slug' => $content->slug,
                    'question' => $content->question,
                    'cached_data' => $cachedData,
                ]);
            }

            $this->line('--------------------');
        }
    }
}
