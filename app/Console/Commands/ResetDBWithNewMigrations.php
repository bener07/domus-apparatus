<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetDBWithNewMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets the database with migrate:fresh and seeds the database with db:seed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dropping Databsae and applying migrations');
        $result = Artisan::call('migrate:fresh');
        if($result){
            $this->error("Error dropping database and applying migrations, run 'php artisan migrate:fresh'");
            return 1;
        }
        $this->info('Seeding Database');
        $result = Artisan::call('db:seed');
        if($result){
            $this->error("Error seeding database, run 'php artisan db:seed'");
            return 1;
        }
        return 0;
    }
}
