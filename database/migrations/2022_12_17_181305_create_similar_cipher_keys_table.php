<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimilarCipherKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cipher_key_similarities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('cipher_key_similarity', function (Blueprint $table) {
            $table->unsignedBigInteger('cipher_key_similarity_id');
            $table->unsignedBigInteger('cipher_key_id');

            $table->foreign('cipher_key_similarity_id')
                ->references('id')
                ->on('cipher_key_similarities')
                ->onDelete('cascade');

            $table->foreign('cipher_key_id')
                ->references('id')
                ->on('cipher_keys')
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
        Schema::dropIfExists('similar_cipher_keys');
    }
}
