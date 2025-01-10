<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;

class ManageDepartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:departments {department_name} {details} {description?} {--create} {--remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $department_name = $this->argument('department_name');
        $details = $this->argument('details');
        $description = $this->argument('description');
        if($this->option('remove') != null){
            $tag = Department::findTag($department_name);
            if($tag!==null){
                $tag->delete();
                $this->line("Tag " . $department_name . " deleted successfully.");
                return 0;
            }
            $this->line("Tag ".$department_name." not found.");
            return 0;
        }
        if($this->option('create') != null){
            $tag = Department::create([
                'name' => $department_name,
                'details' => $details,
                'manager_id' => 1
            ]);
            $this->line($tag);
        }
        return 0;
    }
}
