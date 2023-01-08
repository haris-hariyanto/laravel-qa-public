<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Question;

class LinkStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:link:stats';

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
        $questionsWithRecommendations = Question::where('have_recommendations', 'Y')->count();
        $questionsWithoutRecommendations = Question::where('have_recommendations', 'N')->count();

        $this->line('Stats:');
        $this->line('[ * ] With recommendations : ' . $questionsWithRecommendations);
        $this->line('[ * ] Without recommendations : ' . $questionsWithoutRecommendations);
    }
}
