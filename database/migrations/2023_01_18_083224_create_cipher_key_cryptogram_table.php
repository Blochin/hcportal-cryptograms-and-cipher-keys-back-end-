<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCipherKeyCryptogramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cipher_key_cryptogram', function (Blueprint $table) {

            $table->unsignedBigInteger('cipher_key_id');
            $table->unsignedBigInteger('cryptogram_id');

            $table->foreign('cipher_key_id')
                ->references('id')
                ->on('cipher_keys')
                ->onDelete('cascade');

            $table->foreign('cryptogram_id')
                ->references('id')
                ->on('cryptograms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cipher_key_cryptogram');
    }
}
