<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RolesSeeder::class);

        $user = User::create([
            'name' => 'Bernas',
            'email' => 'bernandre07@gmail.com',
            'password' => Hash::make('teste123#'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10)
        ]);
        $user->addRole('admin');
        User::create([
            'name' => 'tmonky',
            'email' => 'a10801@csmiguel.pt',
            'password' => Hash::make('tmonky'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10)
        ]);
        $this->call(TagsSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
