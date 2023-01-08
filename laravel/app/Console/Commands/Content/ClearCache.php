<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\ContentCache;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:cache:clear';

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
        $this->info('Menghapus cache');
        ContentCache::truncate();
        $this->info('Cache dihapus');
    }
}
