<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalizedTransriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digitalized_transcriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cipher_key_id')->index()->nullable();
            $table->string('digitalized_version')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('digitalization_date')->nullable();
            $table->unsignedBigInteger('created_by')->index()->nullable();

            $table->foreign('cipher_key_id')
                ->references('id')
                ->on('cipher_keys');

            $table->foreign('created_by')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('digitalized_transriptions');
    }
}
