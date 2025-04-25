<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CallModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modes = [
            [
                'name' => 'AUTO'
            ],
            [
                'name' => 'MANUAL'
            ]
        ];


        DB::table('call_modes')->insert($modes);
    }
}
