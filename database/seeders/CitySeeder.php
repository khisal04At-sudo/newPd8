<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        // خريطة للتحويل من الإنجليزية إلى العربية في حال وجود بيانات قديمة
        $translations = [
            'Tripoli' => 'طرابلس',
            'Benghazi' => 'بنغازي',
            'Misrata' => 'مصراتة',
            'Zawia' => 'الزاوية',
            'Tobruk' => 'طبرق',
            'Sabha' => 'سبها',
            'Al Bayda' => 'البيضاء',
            'Gharyan' => 'غريان',
            'Sirte' => 'سرت',
            'Khoms' => 'الخمس',
            'Derna' => 'درنة',
            'Zliten' => 'زليتن',
        ];

        foreach ($translations as $en => $ar) {
            \App\Models\City::where('name', $en)->update(['name' => $ar]);
        }

        $cities = [
            'طرابلس', 'بنغازي', 'مصراتة', 'سرت', 'زليتن', 'الخمس', 'الزاوية', 
            'صبراتة', 'زواره', 'العجيلات', 'سرمان', 'رقدالين', 'الجميل', 
            'غريان', 'ترهونة', 'بني وليد', 'مسلاتة', 'يفرن', 'جادو', 'الرجبان', 
            'الزنتان', 'نالوت', 'غدامس', 'ككلة', 'سبها', 'مرزق', 'أوباري', 
            'غات', 'براك الشاطئ', 'الجفرة', 'هون', 'ودان', 'سوكنة', 'الكفرة', 
            'القطرون', 'تراغن', 'إجدابيا', 'البيضاء', 'طبرق', 'درنة', 'شحات', 
            'المرج', 'البريقة', 'تاجوراء', 'القره بوللي', 'قصر بن غشير', 
            'العزيزية', 'الزهراء', 'العامرية', 'المعمورة'
        ];

        foreach ($cities as $city) {
            \App\Models\City::updateOrCreate(['name' => $city]);
        }
    }
}
