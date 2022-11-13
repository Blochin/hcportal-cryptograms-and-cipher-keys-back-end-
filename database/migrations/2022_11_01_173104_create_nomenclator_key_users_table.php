<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclatorKeyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cipher_key_persons', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('cipher_key_id');
            $table->boolean('is_main_user')->default(false);

            $table->foreign('person_id')
                ->references('id')
                ->on('persons');

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
        Schema::dropIfExists('nomenclator_key_users');
    }
}
