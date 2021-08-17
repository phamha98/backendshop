<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->timestamps();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_user');
            $table->string('id_role');
            //$table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
           // $table->foreign('id_role')->references('id')->on('roles')->onDelete('CASCADE');
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
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
}
