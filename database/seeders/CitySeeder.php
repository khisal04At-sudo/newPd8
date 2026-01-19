<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\City::create(['name' => 'طرابلس']);
        \App\Models\City::create(['name' => 'بنغازي']);
        \App\Models\City::create(['name' => 'مصراتة']);
        \App\Models\City::create(['name' => 'الزاوية']);
        \App\Models\City::create(['name' => 'طبرق']);
        \App\Models\City::create(['name' => 'سبها']);
        \App\Models\City::create(['name' => 'البيضاء']);
        \App\Models\City::create(['name' => 'غريان']);
    }
}
