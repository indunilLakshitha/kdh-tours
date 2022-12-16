<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('img_1')->nullable();
            $table->unsignedBigInteger('img_2')->nullable();
            $table->unsignedBigInteger('img_3')->nullable();
            $table->unsignedBigInteger('img_4')->nullable();
            $table->unsignedBigInteger('img_5')->nullable();
            $table->string('title');
            $table->string('location');
            $table->integer('price');
            $table->tinyInteger('price_type')->comment('1 =day 2 = 1km')->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destinations');
    }
};
