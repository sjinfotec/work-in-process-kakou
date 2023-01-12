<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_date', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('work_date')->nullable()->comment('作業日');
            $table->char('product_code', 6)->nullable()->comment('伝票番号');

            $table->string('departments_name', 10)->nullable()->comment('部署');
            $table->tinyInteger('departments_code')->nullable()->comment('部署コード');
            $table->string('work_name', 20)->nullable()->comment('作業');
            $table->smallInteger('work_code')->nullable()->comment('作業コード');
            $table->string('process_name', 50)->nullable()->comment('工程名');
            $table->string('status', 10)->nullable()->comment('ステータス');

            $table->string('created_user', 10)->nullable()->comment('作成ユーザー');
            $table->string('updated_user', 10)->nullable()->comment('修正ユーザー');
            $table->dateTime('created_at')->nullable()->comment('作成時間');
            $table->dateTime('updated_at')->nullable()->comment('修正時間');
            $table->boolean('is_deleted')->nullable()->comment('削除フラグ')->default(0);

       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_date');
    }
}
