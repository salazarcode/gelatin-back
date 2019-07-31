<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Details extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("details", function($table){
            $table->bigIncrements("id");
            $table->bigInteger("user_id");
            $table->string("sexo");
            $table->string("nombre");
            $table->string("ubicacion");
            $table->integer("edad");
            $table->decimal("estatura");
            $table->decimal("peso");
            $table->decimal("cintura");
            $table->decimal("peso_ideal");
            $table->bigInteger("intensidad_programa");
            $table->bigInteger("actividad_fisica_actual");
            $table->bigInteger("actividad_fisica_meta");
            $table->string("profile_picture");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("details");
    }
}
