<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تأكد من وجود مدينة واحدة على الأقل لتجنب خطأ المفتاح الأجنبي
        $city = \App\Models\City::first();
        
        if (!$city) {
            $city = \App\Models\City::create(['name' => 'طرابلس']);
        }

        User::updateOrCreate(
            ['email' => 'admin@athera.ly'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'city_id' => $city->id,
                'gender' => 'male',
                'birth_date' => '1990-01-01',
                'is_verified' => true,
                'status' => 1,
            ]
        );
    }
}
