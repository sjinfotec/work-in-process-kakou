<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->char('product_code', 6)->nullable()->comment('伝票番号');
            $table->char('serial_code', 4)->nullable()->comment('整理番号');
            $table->char('rep_code', 4)->nullable()->comment('担当');
            $table->dateTime('after_due_date')->nullable()->comment('納期');

            $table->string('customer', 255)->nullable()->comment('得意先');
            $table->string('product_name', 255)->nullable()->comment('品名');
            $table->string('end_user', 255)->nullable()->comment('エンドユーザー');
            $table->string('quantity', 50)->nullable()->comment('数量');
            $table->string('status', 10)->nullable()->comment('ステータス');

            $table->string('process_1', 50)->nullable()->comment('工程１');
            $table->string('departments_name_1', 10)->nullable()->comment('部署１');
            $table->tinyInteger('departments_code_1')->nullable()->comment('部署コード１');
            $table->string('work_name_1', 20)->nullable()->comment('作業１');
            $table->smallInteger('work_code_1')->nullable()->comment('作業コード１');
            $table->dateTime('start_process_date_1')->nullable()->comment('工程１開始日');
            $table->dateTime('end_process_date_1')->nullable()->comment('工程１終了日');

            $table->string('process_2', 50)->nullable()->comment('工程２');
            $table->string('departments_name_2', 10)->nullable()->comment('部署２');
            $table->tinyInteger('departments_code_2')->nullable()->comment('部署コード２');
            $table->string('work_name_2', 20)->nullable()->comment('作業２');
            $table->smallInteger('work_code_2')->nullable()->comment('作業コード２');
            $table->dateTime('start_process_date_2')->nullable()->comment('工程２開始日');
            $table->dateTime('end_process_date_2')->nullable()->comment('工程２終了日');

            $table->string('process_3', 50)->nullable()->comment('工程３');
            $table->string('departments_name_3', 10)->nullable()->comment('部署３');
            $table->tinyInteger('departments_code_3')->nullable()->comment('部署コード３');
            $table->string('work_name_3', 20)->nullable()->comment('作業３');
            $table->smallInteger('work_code_3')->nullable()->comment('作業コード３');
            $table->dateTime('start_process_date_3')->nullable()->comment('工程３開始日');
            $table->dateTime('end_process_date_3')->nullable()->comment('工程３終了日');
            
            $table->string('comment', 255)->nullable()->comment('コメント');

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
        Schema::dropIfExists('process_details');
    }
}
