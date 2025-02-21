<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClassRoom;
use App\Models\Discipline;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClassRoom::create([
            "name"=> "Sala de Ciências",
            "capacity" => 20,
            "location" => "2025E",
            "department_id" => 1
        ]);
        Discipline::create([
            "name" => "Matemática",
            "details" => "Aula de matemática"
        ]);
    }
}
