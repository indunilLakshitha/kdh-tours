<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VedioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $title = ['vedio one','vedio two','vedio three'];
        $summary = ['summary one','summary two','summary three'];
        $thumbnail = [
       'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqLODrAcFh4EE9VC1Uh3EQYjOJSO5oPDL9qw&usqp=CAU',
       'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqLODrAcFh4EE9VC1Uh3EQYjOJSO5oPDL9qw&usqp=CAU',
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqLODrAcFh4EE9VC1Uh3EQYjOJSO5oPDL9qw&usqp=CAU'
    ];

    foreach($title as $key=>$t){
        $vedio = new Video();
        $vedio->title = $title[$key];
        $vedio->summary = $summary[$key];
        $vedio->thumbnail = $thumbnail[$key];
        $vedio->status = 1;
        $vedio->save();
    }

    }
}
