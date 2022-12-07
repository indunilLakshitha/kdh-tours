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
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('summery');
            $table->string('thumbnail');
            $table->text('detail_description');
            $table->date('opening_date');
            $table->time('opening_time');
            $table->date('closing_date');
            $table->time('closing_time');
            $table->string('venue');
            $table->string('place_url');
            $table->text('place_suppliment');
            $table->integer('capacity')->default(0);
            $table->text('instructor_profile');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('reception_status')->default(0);

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
        Schema::dropIfExists('workshops');
    }
};
