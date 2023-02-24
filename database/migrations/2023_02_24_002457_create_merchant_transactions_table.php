<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_transactions', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')->references('id')->on('merchants');
            
            $table->string('status')->default('pending');
            $table->decimal('amount');
            $table->string('ticker');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('merchant_transactions');
    }
};
