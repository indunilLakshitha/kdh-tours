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
        Schema::table('user_report_activity_details', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0=not_filled / 1=filled');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_report_activity_details', function (Blueprint $table) {
            //
        });
    }
};
