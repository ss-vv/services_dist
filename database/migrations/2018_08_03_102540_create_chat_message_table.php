<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
	        $table->bigIncrements('id');
	        $table->integer('from_uid')->comment('发送者ID');
	        $table->integer('to_uid')->comment('接收者ID');
	        $table->tinyInteger('status')->default(0)->comment('接收状态： 0 未接收，1：已接收');
	        $table->tinyInteger('u_type')->comment('发送者类型: 1：玩家 2：客服');
	        $table->text('content')->comment('发送的消息');
	        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            //
        });
    }
}
