<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCipherKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cipher_keys', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('signature')->unique()->nullable();
            $table->text('complete_structure');
            $table->text('used_chars')->nullable();
            $table->string('cipher_type')->nullable();
            $table->string('key_type')->nullable();
            $table->timestamp('used_from')->nullable();
            $table->timestamp('used_to')->nullable();
            $table->string('used_around')->nullable();

            $table->unsignedBigInteger('folder_id')->index()->nullable();
            $table->unsignedBigInteger('location_id')->index()->nullable();
            $table->unsignedBigInteger('language_id')->index();
            $table->unsignedBigInteger('group_id')->index()->nullable();
            $table->string('state');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('folder_id')
                ->references('id')
                ->on('folders')
                ->onDelete('SET NULL');

            $table->foreign('location_id')
                ->references('id')
                ->on('locations');

            $table->foreign('language_id')
                ->references('id')
                ->on('languages');

            $table->foreign('group_id')
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
        Schema::dropIfExists('cipher_keys');
    }
}
