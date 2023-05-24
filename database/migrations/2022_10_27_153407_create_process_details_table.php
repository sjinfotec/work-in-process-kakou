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
            $table->dateTime('after_due_date')->nullable()->comment('納期日');

            $table->string('customer', 255)->nullable()->comment('得意先');
            $table->string('product_name', 255)->nullable()->comment('品名');
            $table->string('end_user', 255)->nullable()->comment('エンドユーザー');
            $table->string('quantity', 50)->nullable()->comment('数量');

            $table->dateTime('receive_date')->nullable()->comment('印刷開始日');
            $table->dateTime('platemake_date')->nullable()->comment('下版日');

            $table->string('status', 10)->nullable()->comment('ステータス');
            $table->string('comment', 255)->nullable()->comment('コメント');

            $table->string('created_user', 50)->nullable()->comment('作成ユーザー');
            $table->string('updated_user', 50)->nullable()->comment('修正ユーザー');
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
