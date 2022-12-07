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
        Schema::create('user_has_workshop_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workshop_has_assignments_id');
            $table->unsignedBigInteger('user_has_workshops_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('workshop_id');
            $table->string('sumbmit_url',512)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = not submitted');
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
        Schema::dropIfExists('user_has_workshop_activities');
    }
};
