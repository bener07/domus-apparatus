<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tags;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        Tags::create([
            'name' => 'Default',
            'details' => 'Default tag',
            'description' => 'Just the default tag used in the database',
            'owner_id' => 1
        ]);
        for ($tag=0; $tag < 5; $tag++) { 
            Tags::create([
                'name' => $faker->name,
                'details' => $faker->sentence,
                'description' => $faker->paragraph,
                'owner_id'=> 1
            ]);
        }
    }
}
