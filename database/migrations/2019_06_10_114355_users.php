<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("users", function($table){
            $table->bigIncrements("id");
            $table->bigInteger("pool_id");
            $table->bigInteger("role_id");
            $table->string("email")->unique();
            $table->string("password");
            $table->string("session_token");
            $table->string("session_recovery");
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
        Schema::dropIfExists("users");
    }
}
