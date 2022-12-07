<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('industries')->truncate();
        DB::table('industries')->insert([
            [
                'id'=>1,
                'name'=>'農業，林業'
            ],
            [
                'id'=>2,
                'name'=>'漁業'
            ],
            [
                'id'=>3,
                'name'=>'鉱業，採石業，砂利採取業'
            ],
            [
                'id'=>4,
                'name'=>'建設業'
            ],
            [
                'id'=>5,
                'name'=>'製造業'
            ]
            ,
            [
                'id'=>6,
                'name'=>'電気・ガス・熱供給・水道業'
            ]
            ,
            [
                'id'=>7,
                'name'=>'情報通信業'
            ]
            ,
            [
                'id'=>8,
                'name'=>'運輸業，郵便業'
            ]
            ,
            [
                'id'=>9,
                'name'=>'卸売業・小売業'
            ]
            ,
            [
                'id'=>10,
                'name'=>'金融業・保険業'
            ]
            ,
            [
                'id'=>11,
                'name'=>'不動産業，物品賃貸業'
            ]
            ,
            [
                'id'=>12,
                'name'=>'学術研究，専門・技術サービス業'
            ]
            ,
            [
                'id'=>13,
                'name'=>'宿泊業，飲食サービス業'
            ]
            ,
            [
                'id'=>14,
                'name'=>'教育，学習支援業'
            ],
            [
                'id'=>15,
                'name'=>'医療，福祉'
            ],
            [
                'id'=>16,
                'name'=>'複合サービス事業'
            ],
            [
                'id'=>17,
                'name'=>'サービス業（他に分類されないもの）'
            ],
            [
                'id'=>18,
                'name'=>'公務（他に分類されるものを除く）'
            ],
            [
                'id'=>19,
                'name'=>'その他'
            ]
        ]);
    }
}
