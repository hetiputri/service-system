<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefGajiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ref_gaji')->insert([
            ['id_pp'=>4,'masa_kerja'=>10,'id_gol'=>42,'gaji'=>2941600],
            ['id_pp'=>8,'masa_kerja'=>0,'id_gol'=>11,'gaji'=>1685700],
            ['id_pp'=>4,'masa_kerja'=>12,'id_gol'=>42,'gaji'=>3029400],
            ['id_pp'=>4,'masa_kerja'=>0,'id_gol'=>41,'gaji'=>2436100],
            ['id_pp'=>4,'masa_kerja'=>2,'id_gol'=>41,'gaji'=>2508900],
            ['id_pp'=>4,'masa_kerja'=>4,'id_gol'=>41,'gaji'=>2583800],
            ['id_pp'=>4,'masa_kerja'=>6,'id_gol'=>41,'gaji'=>2660900],
            ['id_pp'=>4,'masa_kerja'=>8,'id_gol'=>41,'gaji'=>2740400],
            ['id_pp'=>4,'masa_kerja'=>10,'id_gol'=>41,'gaji'=>2822200],
            ['id_pp'=>4,'masa_kerja'=>12,'id_gol'=>41,'gaji'=>2906500],
            ['id_pp'=>8,'masa_kerja'=>2,'id_gol'=>11,'gaji'=>1738800],
            ['id_pp'=>4,'masa_kerja'=>14,'id_gol'=>41,'gaji'=>2993200],
            ['id_pp'=>4,'masa_kerja'=>16,'id_gol'=>41,'gaji'=>3082800],
            ['id_pp'=>4,'masa_kerja'=>18,'id_gol'=>41,'gaji'=>3174700],
            ['id_pp'=>4,'masa_kerja'=>20,'id_gol'=>41,'gaji'=>3269400],
            ['id_pp'=>4,'masa_kerja'=>22,'id_gol'=>41,'gaji'=>3367100],
            ['id_pp'=>4,'masa_kerja'=>24,'id_gol'=>41,'gaji'=>3467600],
            ['id_pp'=>4,'masa_kerja'=>26,'id_gol'=>41,'gaji'=>3571100],
            ['id_pp'=>4,'masa_kerja'=>0,'id_gol'=>34,'gaji'=>2337300],
            ['id_pp'=>4,'masa_kerja'=>2,'id_gol'=>34,'gaji'=>2407100],
            ['id_pp'=>6,'masa_kerja'=>11,'id_gol'=>13,'gaji'=>1359500],
            ['id_pp'=>4,'masa_kerja'=>4,'id_gol'=>34,'gaji'=>2478900],
            ['id_pp'=>6,'masa_kerja'=>13,'id_gol'=>13,'gaji'=>1392900],
            ['id_pp'=>4,'masa_kerja'=>6,'id_gol'=>34,'gaji'=>2552900],
            ['id_pp'=>8,'masa_kerja'=>4,'id_gol'=>11,'gaji'=>1793500],
        ]);
    }
}