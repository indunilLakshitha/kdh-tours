<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->truncate();
        DB::table('positions')->insert([
            [
                'id'=>1,
                'name'=>'会長（取締役会長）'
            ],
            [
                'id'=>2,
                'name'=>'社長（代表取締役社長）'
            ],
            [
                'id'=>3,
                'name'=>'社長（代表取締役社長）'
            ],
            [
                'id'=>4,
                'name'=>'専務（取締役専務）'
            ],
            [
                'id'=>5,
                'name'=>'常務 （取締役常務）'
            ]
            ,
            [
                'id'=>6,
                'name'=>'監査役'
            ]
            ,
            [
                'id'=>7,
                'name'=>'部長'
            ]
            ,
            [
                'id'=>8,
                'name'=>'次長'
            ]
            ,
            [
                'id'=>9,
                'name'=>'課長'
            ]
            ,
            [
                'id'=>10,
                'name'=>'係長'
            ]
            ,
            [
                'id'=>11,
                'name'=>'主任'
            ]
            ,
            [
                'id'=>12,
                'name'=>'一般社員'
            ]
        ]);
    }
}
