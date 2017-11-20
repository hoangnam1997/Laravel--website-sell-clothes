<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Message', function (Blueprint $table) {
            $table->increments('id');
            // $table->primary('id');
            $table->string('Description');
            $table->integer('ID_Users');
            $table->foreign('ID_Users')->references('id')->on('Users');
            $table->date('CreateDate');
            $table->boolean('IsNotify');
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
        Schema::dropIfExists('Message');
    }
}
