<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::unprepared(file_get_contents(__DIR__ . '/../sql/city.sql'));
        DB::unprepared("INSERT INTO `chain` VALUES ('100000', '中国', '0', '中国', '0', '', '', '中国', '116.368', '39.9151', 'China');");
    }
}
