<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Models\Answer;

class DeleteData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:delete';

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
        $startID = $this->ask('Start ID', 0);
        $endID = $this->ask('End ID', 0);

        if (!is_numeric($startID) || !is_numeric($endID)) {
            $this->error('ID harus angka');
            return;
        }

        $this->line('[*] Menghapus pertanyaan');
        $deletedQuestion = Question::where('id', '>=', $startID)->where('id', '<=', $endID)->delete();
        $this->line('[*] ' . $deletedQuestion . ' pertanyaan dihapus');

        $this->line('[*] Menghapus jawaban');
        $deletedAnswer = Answer::where('question_id', '>=', $startID)->where('question_id', '<=', $endID)->delete();
        $this->line('[*] ' . $deletedAnswer . ' jawaban dihapus');
    }
}
