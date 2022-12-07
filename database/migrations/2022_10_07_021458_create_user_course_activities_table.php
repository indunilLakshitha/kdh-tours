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
        Schema::create('user_course_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->index()->constrained('courses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('user_id')->index()->constrained('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('video_id')->index()->constrained('videos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('total_watch_time')->nullable()->default(0)->comment('100s time by seconds');
            $table->tinyInteger('status')->default(0)->comment('0=completed / 1=not_completed');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_course_activities');
    }
};
