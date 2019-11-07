<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserLoginType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_loginType', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('loginType_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('loginType_id')->references('id')->on('login_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_loginType');
    }
}
