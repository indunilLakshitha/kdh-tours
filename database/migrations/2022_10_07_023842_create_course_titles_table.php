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
        Schema::create('course_titles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->index()->constrained('courses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('content')->nullable();
            $table->integer('order')->comment('Title order');
            $table->tinyInteger('status')->nullable()->comment('0= / 1=');
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
        Schema::dropIfExists('course_titles');
    }
};
