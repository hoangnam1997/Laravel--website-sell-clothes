<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Promotion', function (Blueprint $table) {
            $table->increments('id');
            $table->primary('id');
            $table->string('Picture');
            $table->string('Description');
            $table->string('Name');
            $table->string('Discount');
            $table->string('BasePurchase');
            $table->date('StartDate');
            $table->date('EndDate');
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
        Schema::dropIfExists('Promotion');
    }
}
