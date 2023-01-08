<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Import;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PhpZip\ZipFile;

class ImportContents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:import';

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
        
        $getFiles = Http::get('https://enchanting-buttercream-108d13.netlify.app/qa-db-ybzdgjrtmt.json');
        $files = json_decode($getFiles->body(), true);

        $filesToImport = [];
        $filesURLs = [];
        foreach ($files as $file) {
            if (!in_array($file['url'], $filesImported)) {
                $code = $file['fileName'] . ' (' . $file['url'] . ')';
                $filesToImport[] = $code;
                $filesURLs[$code] = $file['url'];
            }
        }
        
        if (count($filesToImport) == 0) {
            $this->line('[ * ] Semua file sudah diimport');
            return false;
        }

        $fileToImport = $this->choice(
            'File untuk diimport',
            $filesToImport,
            0
        );
        $fileURL = $filesURLs[$fileToImport];
        
        $this->line('[ * ] Mendownload file ' . $fileToImport);
        $this->getPackedFile($fileURL);

        Import::firstOrCreate([
            'file_name' => $fileURL,
        ]);

        return true;
    }

    public function getPackedFile($fileURL)
    {
        $saveDestination = 'packed-files/packed-files.zip';

        Storage::put($saveDestination, file_get_contents($fileURL));

        $this->line('[ * ] File telah didownload');
        $this->line('[ * ] Mengekstrak file');
        $zipFile = new ZipFile();
        $zipFile
            ->openFile(storage_path('app/' . $saveDestination))
            ->extractTo(storage_path('app/packed-files'));
        $this->line('[ * ] File telah diekstrak');
        
        Storage::delete($saveDestination);
    }
}
