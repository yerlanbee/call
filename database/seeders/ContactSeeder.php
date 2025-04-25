<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Клиент'
            ],
            [
                'name' => 'Не ответил'
            ],
            [
                'name' => '3-лицо'
            ],
        ];

        DB::table('contacts')->insert($contacts);
    }
}
