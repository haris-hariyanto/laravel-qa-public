<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ClearExpiredTemAvatarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avatar:clear';

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
        $currentTimestamp = time();
        $expiredTemAvatars = User::where('avatar_tem_expired', '<>', 0)->where('avatar_tem_expired', '<=', $currentTimestamp)->get();

        foreach ($expiredTemAvatars as $expiredTemAvatar) {
            $avatarTemPath = $expiredTemAvatar->avatar_tem_path;
            Storage::disk('s3')->delete($avatarTemPath);

            $expiredTemAvatar->update([
                'avatar_tem' => null,
                'avatar_tem_path' => null,
                'avatar_tem_expired' => 0,
            ]);
            
            $this->line('[ * ] ' . $expiredTemAvatar->username);
        }
    }
}
