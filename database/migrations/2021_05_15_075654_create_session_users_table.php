<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token');
            $table->string('refresh_token');
            $table->dateTime('token_expried');
            $table->dateTime('refresh_token_expried');
            $table->bigInteger('id_user');
            //$table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
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
        Schema::dropIfExists('session_users');
    }
}