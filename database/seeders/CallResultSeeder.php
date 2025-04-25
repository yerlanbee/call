<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CallResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $callResults = [
            [
                'name' => 'Отложен платеж'
            ],
            [
                'name' => 'Не обещает'
            ],
            [
                'name' => 'Перезвон'
            ],
            [
                'name' => 'Недозвон'
            ],
            [
                'name' => 'Контроль обещаний'
            ],
            [
                'name' => '-'
            ]
        ];

        DB::table('call_results')->insert($callResults);
    }
}
