<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('District', function (Blueprint $table) {
            $table->increments('id');
            // $table->primary('id');
            $table->string('Name');
            $table->string('Description');
            $table->integer('ID_City');
            $table->foreign('ID_City')->references('id')->on('City');
            $table->boolean('IsDelete');
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
        Schema::dropIfExists('District');
    }
}
