<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceScopeUsersTable extends Migration
{
    /**
     * Run the migrations.
     * Table for User Permission to scope
     * @return void
     */
    public function up()
    {
        Schema::create('service_scope_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('loginType_id');
            $table->string('scope_id');       
            $table->boolean('permission');       
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('loginType_id')->references('id')->on('login_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_scope_users');
    }
}
