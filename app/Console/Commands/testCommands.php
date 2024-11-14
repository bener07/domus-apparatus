<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SocialLinks;
use App\Models\User;

class testCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command {user} {platform?} {--create-social}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just used to test new stuff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::find($this->argument('user'));
        $user->socialLinks;
        if($this->option('create-social') != null){
            $social = $user->socialLinks()->create([
                'platform' => $this->argument('platform'),
                'user_id' => $user->id,
                'social_id' => '123456789',
            ]);
            $this->line($social);
        }
        $this->line($user);
    }
}
