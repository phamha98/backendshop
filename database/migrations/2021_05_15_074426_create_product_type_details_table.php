<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTypeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_type_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_type_main');
            $table->string("name");
            $table->string("details",5000);
            $table->float('price',10,0);
            $table->float('sale',8,0);//
            $table->enum("new",["1","0"])->default('1');
            $table->string('img');
            $table->enum('gender',["nam","nu","tat"])->default('tat');
            $table->enum('type',["open","close"])->default('open');
           // $table->foreign('id_type_main')->references('id')->on('product_type_mains')->onDelete('CASCADE');
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
        Schema::dropIfExists('product_type_details');
    }
}
