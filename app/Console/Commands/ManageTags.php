<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tags;

class ManageTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tags {tag_name} {details?} {description?} {--create} {--remove}';

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
        $tag_name = $this->argument('tag_name');
        $details = $this->argument('details');
        $description = $this->argument('description');
        if($this->option('remove') != null){
            $tag = Tags::findTag($tag_name);
            if($tag!==null){
                $tag->delete();
                $this->line("Tag " . $tag_name . " deleted successfully.");
                return 0;
            }
            $this->line("Tag ".$tag_name." not found.");
            return 0;
        }
        if($this->option('create') != null){
            $tag = Tags::create([
                'name' => $tag_name,
                'details' => $details,
                'image' => 'default_image.jpg',
                'description' => $description
            ]);
            $this->line($tag);
        }
        return 0;
    }
}
