<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tags;
use App\Models\Product;

class AddTagToProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:tags-to-products {Product_id} {tag_id?} {--remove} {--remove-all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage the tags to the products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $Product = Product::find($this->argument('Product_id'));
        $tag_id = $this->argument('tag_id');
        if($this->option('remove-all')){
            foreach ($Product->tags as $tag) {
                $Product->removeTag($tag->id);
            }
        }
        if($this->option('remove')){
            $this->line('Remove Tag');
            $Product->removeTag($tag_id);
        }
        if($tag_id !== null && !$this->option('remove')){
            $Product->addTag($tag_id);
        }
        $Product = Product::find($Product->id);
        $tags = $Product->tags;
        $this->line(json_encode($Product, JSON_PRETTY_PRINT));
        return 0;
    }
}
