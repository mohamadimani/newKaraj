<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Province::exists()) {
            return;
        }

        Province::insert([
            [
                'slug' => 'Tehran',
                'name' => 'تهران',
                'sort' => 1,
            ],
            [
                'slug' => 'Isfahan',
                'name' => 'اصفهان',
                'sort' => 2,
            ],
            [
                'slug' => 'Alborz',
                'name' => 'البرز',
                'sort' => 3,
            ],
            [
                'slug' => 'East_Azerbaijan',
                'name' => 'آذربایجان شرقی',
                'sort' => 4,
            ],
            [
                'slug' => 'West_Azerbaijan',
                'name' => 'آذربایجان غربی',
                'sort' => 5,
            ],
            [
                'slug' => 'Ardabil',
                'name' => 'اردبیل',
                'sort' => 6,
            ],
            [
                'slug' => 'Ilam',
                'name' => 'ایلام',
                'sort' => 7,
            ],
            [
                'slug' => 'Bushehr',
                'name' => 'بوشهر',
                'sort' => 8,
            ],
            [
                'slug' => 'Chaharmahal_and_Bakhtiari',
                'name' => 'چهارمحال و بختیاری',
                'sort' => 9,
            ],
            [
                'slug' => 'South_Khorasan',
                'name' => 'خراسان جنوبی',
                'sort' => 10,
            ],
            [
                'slug' => 'Razavi_Khorasan',
                'name' => 'خراسان رضوی',
                'sort' => 11,
            ],
            [
                'slug' => 'North_Khorasan',
                'name' => 'خراسان شمالی',
                'sort' => 12,
            ],
            [
                'slug' => 'Khuzestan',
                'name' => 'خوزستان',
                'sort' => 13,
            ],
            [
                'slug' => 'Zanjan',
                'name' => 'زنجان',
                'sort' => 14,
            ],
            [
                'slug' => 'Semnan',
                'name' => 'سمنان',
                'sort' => 15,
            ],
            [
                'slug' => 'Sistan_and_Baluchestan',
                'name' => 'سیستان و بلوچستان',
                'sort' => 16,
            ],
            [
                'slug' => 'Fars',
                'name' => 'فارس',
                'sort' => 17,
            ],
            [
                'slug' => 'Qazvin',
                'name' => 'قزوین',
                'sort' => 18,
            ],
            [
                'slug' => 'Qom',
                'name' => 'قم',
                'sort' => 19,
            ],
            [
                'slug' => 'Kurdistan',
                'name' => 'کردستان',
                'sort' => 20,
            ],
            [
                'slug' => 'Kerman',
                'name' => 'کرمان',
                'sort' => 21,
            ],
            [
                'slug' => 'Kermanshah',
                'name' => 'کرمانشاه',
                'sort' => 22,
            ],
            [
                'slug' => 'Kohgiluyeh_and_BoyerAhmad',
                'name' => 'کهگیلویه و بویراحمد',
                'sort' => 23,
            ],
            [
                'slug' => 'Golestan',
                'name' => 'گلستان',
                'sort' => 24,
            ],
            [
                'slug' => 'Gilan',
                'name' => 'گیلان',
                'sort' => 25,
            ],
            [
                'slug' => 'Loretta',
                'name' => 'لرستان',
                'sort' => 26,
            ],
            [
                'slug' => 'Mazandaran',
                'name' => 'مازندران',
                'sort' => 27,
            ],
            [
                'slug' => 'Markazi',
                'name' => 'مرکزی',
                'sort' => 28,
            ],
            [
                'slug' => 'Hormozgan',
                'name' => 'هرمزگان',
                'sort' => 29,
            ],
            [
                'slug' => 'Hamadan',
                'name' => 'همدان',
                'sort' => 30,
            ],
            [
                'slug' => 'Yazd',
                'name' => 'یزد',
                'sort' => 31,
            ]
        ]);
    }
}
