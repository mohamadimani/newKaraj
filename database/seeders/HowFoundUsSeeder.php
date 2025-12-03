<?php

namespace Database\Seeders;

use App\Models\FamiliarityWay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HowFoundUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (FamiliarityWay::count()) {
            return;
        }

        FamiliarityWay::insert([
            [
                'title' => 'سرچ گوگل',
                'slug' => 'google_search',
                'sort' => 1,
            ],
            [
                'title' => 'سایت آموزشگاه',
                'slug' => 'site',
                'sort' => 2,
            ],
            [
                'title' => 'اینستاگرام',
                'slug' => 'instagram',
                'sort' => 3,
            ],
            [
                'title' => 'دیوار',
                'slug' => 'divar',
                'sort' => 4,
            ],
            [
                'title' => 'پیامک',
                'slug' => 'sms',
                'sort' => 5,
            ],
            [
                'title' => 'تبلیغات تلوزیونی',
                'slug' => 'tv_ads',
                'sort' => 6,
            ],
            [
                'title' => 'نمایشگاه',
                'slug' => 'exhibition',
                'sort' => 7,
            ],
            [
                'title' => 'معرفی دوستان',
                'slug' => 'by_friends',
                'sort' => 8,
            ],
            [
                'title' => 'یوتیوب',
                'slug' => 'youtube',
                'sort' => 9,
            ],
            [
                'title' => 'تلگرام',
                'slug' => 'telegram',
                'sort' => 10,
            ],
            [
                'title' => 'ایمیل',
                'slug' => 'email',
                'sort' => 11,
            ],
            [
                'title' => 'سازمانی',
                'slug' => 'organizational',
                'sort' => 12,
            ],
            [
                'title' => 'ایزایران',
                'slug' => 'goftino',
                'sort' => 13,
            ],
            [
                'title' => 'گفتینو',
                'slug' => 'is_iran',
                'sort' => 14,
            ],
            [
                'title' => 'تابلو آموزشگاه',
                'slug' => 'school_board',
                'sort' => 15,
            ],
            [
                'title' => 'شعبه دنیز',
                'slug' => 'deniz',
                'sort' => 16,
            ],
            [
                'title' => 'سایر',
                'slug' => 'other',
                'sort' => 9999,
            ]

        ]);
    }
}
