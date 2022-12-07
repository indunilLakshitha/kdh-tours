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
        Schema::create('course_has_post_viewings', function (Blueprint $table) {
            $table->id();
            
            $table->integer('pos')->default(1);
            $table->unsignedBigInteger('course_id');
            $table->tinyInteger('type')->default(0)->comment('0 = worksheet  1 = document');
            $table->string('title')->nullable();
            $table->string('document')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('course_has_post_viewings');
    }
};
