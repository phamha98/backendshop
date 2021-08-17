<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->timestamps();
        });
        Schema::create('role_permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_role');
            $table->string('id_permission');
            //$table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
            //$table->foreign('id_permission')->references('id')->on('permissions')->onDelete('CASCADE');

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
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_permission');
    }
}
