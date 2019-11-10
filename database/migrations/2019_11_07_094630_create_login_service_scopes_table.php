<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginServiceScopesTable extends Migration
{
    /**
     * Run the migrations.
     * Scope for services like facebook, google
     * @return void
     */
    public function up()
    {
        Schema::create('login_service_scopes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('loginType_id');
            $table->string('scope');
            $table->string('display_name');
            $table->string('field_name');
            
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
        Schema::dropIfExists('login_service_scopes');
    }
}
