<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('image');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('bio')->nullable();
            $table->boolean('status')->default(0);
            $table->string('code')->nullable();
            $table->string('firebase')->nullable();
            $table->timestamp('last_seen')->nullable()->default(Carbon::now());
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
