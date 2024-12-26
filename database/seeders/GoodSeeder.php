<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('goods')->insert([
            [
                'name' => 'Coca-cola',
                'type' => 'drink',
                'price' => mt_rand(1000, 10000) / 10,
            ],
            [
                'name' => 'Lipton зелёный чай',
                'type' => 'drink',
                'price' => mt_rand(1000, 10000) / 10,
            ],
            [
                'name' => 'Пепперони',
                'type' => 'pizza',
                'price' => mt_rand(1000, 10000) / 10,
            ],
            [
                'name' => 'Прошуто',
                'type' => 'pizza',
                'price' => mt_rand(1000, 10000) / 10,
            ]
        ]);
    }
}
