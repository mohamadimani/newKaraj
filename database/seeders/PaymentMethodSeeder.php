<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (PaymentMethod::count() > 0) {
            return;
        }

        PaymentMethod::insert([
            [
                'title' => 'پوز',
                'slug' => 'pos',
                'sort' => 1,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'کارت دنیز',
                'slug' => 'fan-amozan-card',
                'sort' => 2,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'کارت پایتخت',
                'slug' => 'paytakht-card',
                'sort' => 3,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'کارت فنی پایتخت',
                'slug' => 'fani-paytakht-card',
                'sort' => 4,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'کارت آموزش برق',
                'slug' => 'amoozesh-bargh-card',
                'sort' => 5,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'درگاه دنیز',
                'slug' => 'fan-amozan-gateway',
                'sort' => 6,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'درگاه پایتخت',
                'slug' => 'paytakht-gateway',
                'sort' => 7,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'درگاه دنیز آنلاین',
                'slug' => 'fan-amozan-online-gateway',
                'sort' => 8,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'نقدی',
                'slug' => 'cash',
                'sort' => 9,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'چک',
                'slug' => 'check',
                'sort' => 10,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'title' => 'ایزایران',
                'slug' => 'isiran',
                'sort' => 11,
                'description' => null,
                'is_active' => true,
                'created_by' => 1,
            ]

        ]);
    }
}
