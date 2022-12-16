<?php

namespace Database\Seeders;

use App\Models\Section\HowItWork;
use App\Models\Section\QnA;
use App\Models\Section\TopCount;
use App\Models\Section\WhyUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $how_it_list = [
            ['title' => 'Find the Right Bike', 'description' => 'Repellendus facilisi ultrices ad culpa qui sit adipiscing! Quidem bibendum quisque. Tempus, maxime repudianda.'],
            ['title' => 'Book it Online', 'description' => 'Sem eget occaecati viverra, corporis aliquam iste laboriosam corrupti tristique. Occaecat tempor! Torquent malesuada.'],
            ['title' => 'Enjoy your Ride', 'description' => 'Blandit modi porro? Sequi sem pharetra duis rhoncus amet ipsum faucibus iusto, labore nisl, porttitor ultrices.'],

        ];
        HowItWork::truncate();
        foreach ($how_it_list as $list) {
            $li = new HowItWork();
            $li->title = $list['title'];
            $li->description = $list['description'];
            $li->save();
        }

        TopCount::truncate();
        $tc = new TopCount();
        $tc->image = 'no image';
        $tc->description_1 = 'Fermentum assumenda, nostrud semper tellus reprehenderit, auctor aliquip officia, adipiscing! Sapien consequuntur consectetuer facere potenti? Incididuntmontes praesent, qui. Venenatis, consequuntur nobis pede.';
        $tc->description_2 = 'Harum incidunt mollis natus dui quas, massa irure cursus odit molestias nemo a cursus. Metus. Mollit irure posuere eget, sociis, aliquip, ipsum tempus turpis. Mollitia, sunt, egestas montes! Sollicitudin! Hendrerit rhoncu';
        $tc->type_1 = 'KDH High Roof';
        $tc->type_2 = 'KDH';
        $tc->type_3 = 'Van';
        $tc->save();


        $qna = [
            [
                'question' => 'How can i select a KDH rent?',
                'answer' => 'Repellendus facilisi ultrices ad culpa qui sit adipiscing! Quidem bibendum quisque. Tempus, maxime repudianda.'
            ],
            [
                'question' => 'How can i select a KDH rent?',
                'answer' => 'Repellendus facilisi ultrices ad culpa qui sit adipiscing! Quidem bibendum quisque. Tempus, maxime repudianda.'
            ],

        ];
        QnA::truncate();
        foreach ($qna as $q) {
            $qn = new QnA();
            $qn->question = $q['question'];
            $qn->answer = $q['answer'];
            $qn->save();
        }

        $why_us_list = [
            [
                'title' => 'Immediate Booking',
                'description' => 'Our calendars are updated in real time. Select your dates and book online.'
            ],
            [
                'title' => 'Online Booking',
                'description' => 'Have your plans changed? Don t worry, you can change the date or cancel your reservation without paying any additional fees.'
            ],
            [
                'title' => 'Refundable Booking',
                'description' => 'You will not find the same vehicle for the same dates at a better price, guaranteed!'
            ],
            [
                'title' => '24/7 Assistance Support',
                'description' => 'Our multilingual customer service team is here to help you have a great vacation.'
            ],

        ];
        WhyUs::truncate();
        foreach ($why_us_list as $list) {
            $li = new WhyUs();
            $li->title = $list['title'];
            $li->description = $list['description'];
            $li->save();
        }
    }
}
