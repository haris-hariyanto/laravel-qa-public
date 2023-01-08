<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Question;
use Illuminate\Support\Str;

class SaveContents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:save';

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
        $this->line('Save Contents');

        $files = Storage::files('packed-files');
        $filesTotal = count($files);

        $this->line('[ * ] Total file yang belum disimpan ke database : ' . $filesTotal);
        $this->line('[ * ] Masukkan jumlah file untuk disimpan');
        $this->line('[ * ] Masukkan * untuk menyimpan semua file');

        $loop = true;
        while ($loop) {
            $limitFiles = $this->ask('Jumlah file', 5000);

            if (is_numeric($limitFiles) || $limitFiles === '*') {
                $loop = false;
            }
        }

        if (Question::latest()->first()) {
            $slugID = Question::latest()->first()->id;
        }
        else {
            $slugID = 1;
        }

        if ($limitFiles === '*') {
            $limitFiles = $filesTotal;
        }

        $currentLoop = 1;
        $lastQuestion = '';
        foreach ($files as $file) {
            $this->line('[ ' . $currentLoop . ' ] Megimport ' . $file);

            $content = Storage::get($file);
            $content = json_decode($content, true);

            if (!$content || count($content['answers']) == 0 || $lastQuestion == $content['question']) {
                $this->error('[ * ] Skip');

                Storage::delete($file);
                $currentLoop++;

                continue;
            }

            $grade = Grade::firstOrCreate([
                'slug' => Str::slug($content['grade']),
                'name' => $content['grade'],
            ]);

            $subject = Subject::firstOrCreate([
                'slug' => Str::slug($content['subject']),
                'name' => $content['subject'],
            ]);

            $slug = preg_replace('/[^A-Za-z0-9 ]/', '', $content['question']);
            $slug = Str::words($slug, 7, '');
            if (strlen($slug) > 70) {
                $slug = substr($slug, 0, 70);
            }
            $slug = Str::slug($slugID . ' ' . $slug);

            // Answer
            $answersToSave = [];
            $count = 1;
            foreach ($content['answers'] as $answer) {
                $answersToSave[] = [
                    'user_id' => $count + 2,
                    'answer' => html_entity_decode($answer),
                    'is_best' => $count == 1 ? 'Y' : 'N',
                    'vote' => $count == 1 ? rand(51, 100) : rand(1, 50),
                ];

                $count++;
            }
            // [END] Answer
            
            $question = Question::create([
                'user_id' => 2,
                'slug' => $slug,
                'question' => html_entity_decode($content['question']),
                'subject_id' => $subject->id,
                'grade_id' => $grade->id,
                'vote' => rand(1, 100),
                'answers_cached' => json_encode($answersToSave), // [2]
            ]);
            $slugID = $question->id;
            $slugID += 1;

            $lastQuestion = $content['question'];

            $answersToSave = [];
            $count = 1;
            foreach ($content['answers'] as $answer) {
                $answersToSave[] = [
                    'user_id' => $count + 2,
                    'answer' => html_entity_decode($answer),
                    'is_best' => $count == 1 ? 'Y' : 'N',
                    'vote' => $count == 1 ? rand(51, 100) : rand(1, 50),
                ];

                $count++;
            }

            $question->answers()->createMany($answersToSave);

            Storage::delete($file);

            $currentLoop++;
            if ($currentLoop > $limitFiles) {
                break;
            }
        }

        return true;
    }
}
