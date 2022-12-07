<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('occupations')->truncate();
        DB::table('occupations')->insert([
            [
                'id'=>1,
                'name'=>'営業'
            ],
            [
                'id'=>2,
                'name'=>'事務・管理'
            ],
            [
                'id'=>3,
                'name'=>'サービス・販売・外食'
            ],
            [
                'id'=>4,
                'name'=>'クリエイティブ（メディア・アパレル・デザイン）'
            ],
            [
                'id'=>5,
                'name'=>'ITエンジニア（システム開発・SE・インフラ）'
            ],
            [
                'id'=>6,
                'name'=>'素材・化学・食品・医薬品技術職'
            ],
            [
                'id'=>7,
                'name'=>'建築・土木技術職'
            ],
            [
                'id'=>8,
                'name'=>'教育・保育・公務員・農林水産・その他'
            ],
            [
                'id'=>9,
                'name'=>'企画・マーケティング・経営・管理職'
            ],
            [
                'id'=>10,
                'name'=>'Weｂ・インターネット・ゲーム'
            ]
            ,
            [
                'id'=>11,
                'name'=>'専門職（コンサルタント・士業・金融・不動産）'
            ],
            [
                'id'=>12,
                'name'=>'エンジニア（機械・電気・電子・半導体・制御）'
            ],
            [
                'id'=>13,
                'name'=>'医療・福祉・介護'
            ]
            ,
            [
                'id'=>14,
                'name'=>'技能工・設備・交通・運輸'
            ]
        ]);
    }
}
