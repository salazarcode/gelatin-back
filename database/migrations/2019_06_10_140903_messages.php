<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Messages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("messages", function($table){
            $table->bigIncrements("id");
            $table->longText("text");
            $table->timestamps();
            $table->bigInteger("file_id")->nullable();
            $table->bigInteger("chat_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("messages");
    }
}
