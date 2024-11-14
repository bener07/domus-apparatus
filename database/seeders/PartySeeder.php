<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Party;
use App\Models\Tags;

class PartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Party::truncate();
        $faker = \Faker\Factory::create();

        for ($i=0; $i < 3; $i++) { 
            $party = Party::create([
                'name' => $faker->name,
                'details'=> $faker->sentence,
                'price' => $faker->numberBetween(10, 100),
                'owner_id' => 1,
                'images' => [
                        'shopping'=>'https://officechai.com/wp-content/uploads/2016/05/online-shoping.jpg',
                        'testing' => 'https://officechai.com/wp-content/uploads/2016/05/3-Photoshop-Funny-CEO-Falls-Asleep-Work-Employees-Edit-Memes.jpg',
                    ],
                'featured_image' => 'https://media.gettyimages.com/id/542095594/photo/birthday-party-in-the-office.jpg?s=612x612&w=0&k=20&c=-QK7XL40VVwucnL4nbi4cMqfuFUGfb8ZFpUQbLsx85E=',
                "location" => $faker->address,
            ]);
            $party->tags()->attach(1);
        }
    }
}
