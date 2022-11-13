<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncryptionPairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encryption_pairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('digitalized_transcription_id')->index()->nullable();
            $table->string('plain_text_unit')->nullable();
            $table->string('cipher_text_unit');

            $table->foreign('digitalized_transcription_id')
                ->references('id')
                ->on('digitalized_transcriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('encryption_pairs');
    }
}
