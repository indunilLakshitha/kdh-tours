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
        Schema::create('user_report_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('course_id')->index()->constrained('courses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->tinyInteger('status')->default(0)->comment('0=temporary / 1=published');
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
        Schema::dropIfExists('user_report_activities');
    }
};
