<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('place_type_id')->unsigned();
            $table->integer('continent_id')->unsigned();
            $table->integer('country_id')->unsigned();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('lat');
            $table->string('lon');
            $table->string('address');
            $table->text('description');
            $table->boolean('status')->default(0);
            $table->string('booking_link')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('place_type_id')->references('id')->on('place_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('continent_id')->references('id')->on('continents')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('places');
    }
}
