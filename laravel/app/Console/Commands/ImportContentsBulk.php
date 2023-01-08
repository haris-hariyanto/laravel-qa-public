<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Import;
use App\Models\Question;
use App\Models\Grade;
use App\Models\Subject;
use PhpZip\ZipFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ImportContentsBulk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:import:save:bulk';

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
        $this->line('Import Contents');

        $filesImported = Import::get()->pluck('file_name')->toArray();
        $files = Storage::disk('importer')->files();

        $fileToImport = [];
        foreach ($files as $file) {
            $fileName = 'https://f004.backblazeb2.com/file/brainlyminer2/' . $file;
            if (!in_array($fileName, $filesImported)) {
                $fileToImport[] = $file;
            }
        }

        if (count($fileToImport) == 0) {
            $this->line('[ * ] Semua file sudah diimport');
            return 0;
        }

        /*
        $fileToImport = $this->choice(
            'File untuk diimport',
            $fileToImport,
            0
        );
        */
        $totalFileToImport = $this->ask('Jumlah file untuk diimport', 1);
        if (!is_numeric($totalFileToImport)) {
            $this->error('[ * ] Masukkan angka');
            return 0;
        }

        for ($i = 1; $i <= $totalFileToImport; $i++) {
            $this->line('[ * ] Mengimport ' . $fileToImport[$i]);

            $this->line('[ * ] Mendownload file');
            $this->getPackedFile($fileToImport[$i]);
    
            Import::firstOrCreate([
                'file_name' => 'https://f004.backblazeb2.com/file/brainlyminer2/' . $fileToImport[$i],
            ]);

            $this->saveAll();
        }
    }

    public function saveAll()
    {
        $this->line('Save Contents');

        $files = Storage::files('packed-files');
        $filesTotal = count($files);

        $limitFiles = '*';

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

            $question->answers()->createMany($answersToSave);

            Storage::delete($file);

            $currentLoop++;
            if ($currentLoop > $limitFiles) {
                break;
            }
        }

        return true;
    }

    public function getPackedFile($file_name)
    {
        Storage::put('packed-files/' . $file_name, Storage::disk('importer')->get($file_name));

        $zipFile = new ZipFile();
        $zipFile
            ->openFile(storage_path('app/packed-files/' . $file_name))
            ->extractTo(storage_path('app/packed-files'));
        
        Storage::delete('packed-files/' . $file_name);
    }
}
