<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentToProcessDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_date', function (Blueprint $table) {
            $table->string('comment', 256)->nullable()->comment('コメント')->after('status');
            $table->string('performance', 100)->nullable()->comment('作業実績')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_date', function (Blueprint $table) {
            $table->dropColumn('charge');
            $table->dropColumn('order_no');
        });
    }
}
