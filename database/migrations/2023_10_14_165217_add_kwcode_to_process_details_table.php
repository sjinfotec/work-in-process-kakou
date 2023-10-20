<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKwcodeToProcessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_details', function (Blueprint $table) {
            $table->smallInteger('wkcode05')->nullable()->comment('加工作業コード5')->after('work_need_days');
            $table->string('wkcom05', 50)->nullable()->comment('加工作業コメント5')->after('work_need_days');
            $table->smallInteger('wkcode04')->nullable()->comment('加工作業コード4')->after('work_need_days');
            $table->string('wkcom04', 50)->nullable()->comment('加工作業コメント4')->after('work_need_days');
            $table->smallInteger('wkcode03')->nullable()->comment('加工作業コード3')->after('work_need_days');
            $table->string('wkcom03', 50)->nullable()->comment('加工作業コメント3')->after('work_need_days');
            $table->smallInteger('wkcode02')->nullable()->comment('加工作業コード2')->after('work_need_days');
            $table->string('wkcom02', 50)->nullable()->comment('加工作業コメント2')->after('work_need_days');
            $table->smallInteger('wkcode01')->nullable()->comment('加工作業コード1')->after('work_need_days');
            $table->string('wkcom01', 50)->nullable()->comment('加工作業コメント1')->after('work_need_days');
            $table->char('category', 4)->nullable()->comment('カテゴリー')->after('work_need_days');
            //
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
            $table->dropColumn('wkcode05');
            $table->dropColumn('wkcom05');
            $table->dropColumn('wkcode04');
            $table->dropColumn('wkcom04');
            $table->dropColumn('wkcode03');
            $table->dropColumn('wkcom03');
            $table->dropColumn('wkcode02');
            $table->dropColumn('wkcom02');
            $table->dropColumn('wkcode01');
            $table->dropColumn('wkcom01');
            $table->dropColumn('category');
            //
        });
    }
}
