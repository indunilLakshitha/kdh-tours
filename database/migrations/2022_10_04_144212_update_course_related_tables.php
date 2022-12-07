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
        
        Schema::table('courses', function (Blueprint $table) {
            $table->decimal('total_watchtime')->nullable()->after('has_reports')->default(0);
            $table->integer('total_vedio_count')->nullable()->after('total_watchtime')->default(0);
            $table->integer('total_report_count')->nullable()->after('total_vedio_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
