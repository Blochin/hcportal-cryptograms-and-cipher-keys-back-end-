<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclatorImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cipher_keys_images', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('cipher_key_id')->index()->nullable();
            $table->boolean('is_local')->boolean(false);
            $table->text('structure')->nullable();
            $table->integer('ordering');
            $table->boolean('has_instructions')->boolean(false);


            $table->foreign('cipher_key_id')
                ->references('id')
                ->on('cipher_keys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nomenclator_images');
    }
}
