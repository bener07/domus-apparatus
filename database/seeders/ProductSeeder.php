<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BaseProducts;
use App\Models\Tags;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product::truncate();
        $faker = \Faker\Factory::create();

        for ($i=0; $i < 3; $i++) {
            $products = BaseProducts::create([
                'name' => $faker->name,
                'details'=> $faker->sentence,
                'featured_image' => 'https://officechai.com/wp-content/uploads/2016/05/online-shoping.jpg',
                'images' => [
                    'shopping'=>'https://officechai.com/wp-content/uploads/2016/05/online-shoping.jpg',
                    'testing' => 'https://officechai.com/wp-content/uploads/2016/05/3-Photoshop-Funny-CEO-Falls-Asleep-Work-Employees-Edit-Memes.jpg',
                ],
                'isbns'=> [
                    $faker->unique()->numberBetween(10000, 100000),
                    $faker->unique()->numberBetween(10000, 100000),
                    $faker->unique()->numberBetween(10000, 100000),
                    $faker->unique()->numberBetween(10000, 100000),
                    $faker->unique()->numberBetween(10000, 100000),
                ],
                'tag' => 1
            ]);
        }
    }
}
