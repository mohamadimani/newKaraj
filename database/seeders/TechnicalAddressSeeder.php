<?php

namespace Database\Seeders;

use App\Models\TechnicalAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TechnicalAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (TechnicalAddress::count()) {
            return;
        }

        // TechnicalAddress::insert([
        //     [
        //         'title' => 'مركز شماره 4 شهيد بهرامي',
        //         'address' => 'چهارراه يافت آباد بلوار معلم نرسيده به ميدان معلم',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مركز شماره 5 شهيد مروتي',
        //         'address' => 'ميدان بهمن نبش پارك سردار جنگل',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مركز شماره 8 زعفرانيه تهران',
        //         'address' => 'زعفرانيه خ اعجازي ميدان اعجازي خ بهزادي ك ميرزايي',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مركز شماره 9مشيريه تهران',
        //         'address' => 'مشيريه-بلوار بوعلي شرقي',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مركز شماره 17شهر ري',
        //         'address' => 'شهر ري سه راه ترانسفور خ اانبار نفت',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مركز1شهيد مهمانچي تهران',
        //         'address' => 'ميدان بهمن ابتداي بزرگراه بعثت',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مرکز 9 دی خودرو(کوئیکا)',
        //         'address' => 'تهران-پیکان شهر-شهرک سرو آزاد-هشتم غربی-جنب باغ',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مرکز9',
        //         'address' => 'مشیریه بلوار بو علی شرقی',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مرکز 17 شهرری',
        //         'address' => 'شهرری جاده قدیم قم خیابان ترانسفور',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'کوییکا',
        //         'address' => 'مرکز کوییکا - پیکانشهر مر کز تخصصی مهارت خودرو',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ],
        //     [
        //         'title' => 'مرکز شماره 1',
        //         'address' => 'میدان بهمن،ابتدای بزرگراه بعثت،نرسیده به چهار راه چیت سازی',
        //         'phone' => '02100000000',
        //         'branch_id' => 1,
        //         'province_id' => 1,
        //         'created_by' => 1,
        //     ]

        // ]);
    }
}
