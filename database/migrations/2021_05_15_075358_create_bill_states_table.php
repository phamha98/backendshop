<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_bill');
            $table->enum('state',["1","2","3","4"])->default('1');
            $table->string('id_user_order');
            $table->string('id_user_confirm')->nullable();;;
            $table->string('id_user_transport')->nullable();;
            $table->string('id_user_cancel')->nullable();;
            $table->timestamps();
           // $table->foreign('id_bill')->references('id')->on('bills')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_states');
    }
}
