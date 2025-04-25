<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CallReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $calReasons = [
            [
                'name' => 'Другое'
            ],
            [
                'name' => 'Да'
            ],
            [
                'name' => 'Клиент молчит'
            ],
            [
                'name' => 'Не берет трубку'
            ],
            [
                'name' => 'Сорвался звонок/Сбой связи'
            ],
            [
                'name' => 'Идет гудок'
            ],
            [
                'name' => 'Бросил трубку'
            ],
        ];

        DB::table('call_reasons')->insert($calReasons);
    }
}
