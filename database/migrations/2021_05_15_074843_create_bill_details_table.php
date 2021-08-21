<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("id_bill");
            $table->string("id_product");
            $table->integer("number");
            $table->float("price");
            $table->timestamps();
            //$table->foreign('id_bill')->references('id')->on('bills')->onDelete('CASCADE');
            //$table->foreign('id_product')->references('id')->on('products')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_details');
    }
}
