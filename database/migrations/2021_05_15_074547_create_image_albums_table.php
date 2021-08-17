<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("id_type_details");
            $table->string('name');
            $table->timestamps();
            //$table->foreign('id_type_details')->references('id')->on('product_type_details')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_albums');
    }
}
