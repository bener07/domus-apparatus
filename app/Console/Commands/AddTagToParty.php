<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tags;
use App\Models\Party;

class AddTagToParty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tags-to-parties {party_id} {tag_id?} {--remove} {--remove-all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage the tags to the parties';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $party = Party::find($this->argument('party_id'));
        $tag_id = $this->argument('tag_id');
        if($this->option('remove-all')){
            foreach ($party->tags as $tag) {
                $party->removeTag($tag->id);
            }
        }
        if($this->option('remove')){
            $this->line('Remove Tag');
            $party->removeTag($tag_id);
        }
        if($tag_id !== null && !$this->option('remove')){
            $party->addTag($tag_id);
        }
        $party = Party::find($party->id);
        $tags = $party->tags;
        $this->line(json_encode($party, JSON_PRETTY_PRINT));
        return 0;
    }
}
