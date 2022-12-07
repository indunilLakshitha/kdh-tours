<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Extension\Table\Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_vedios_and_titles', function (Blueprint $table) {
            $table->id();
            $table->integer('pos')->default(1);
            $table->unsignedBigInteger('course_id');
            $table->tinyInteger('type')->default(0)->comment('0 = major title 1 = vedio');
            $table->unsignedBigInteger('vedio_id')->nullable();
            $table->string('content')->nullable();

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
        Schema::dropIfExists('course_vedios_and_titles');
    }
};
