<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkdaysToProcessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_details', function (Blueprint $table) {
            $table->string('work_need_days', 10)->nullable()->comment('作業必要日数')->after('platemake_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_details', function (Blueprint $table) {
            $table->dropColumn('work_need_days');
        });
    }
}
