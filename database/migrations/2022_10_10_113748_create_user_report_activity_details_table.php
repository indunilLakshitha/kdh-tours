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
        Schema::create('user_report_activity_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_activity_id')->index()->constrained('user_report_activities')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('user_id')->index()->constrained('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('course_id')->index()->constrained('courses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('course_report_id')->index()->constrained('course_has_reports')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('pos')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0 = text  1 = file');
            $table->text('content')->nullable();
            $table->string('document')->nullable();
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
        Schema::dropIfExists('user_report_activity_details');
    }
};
