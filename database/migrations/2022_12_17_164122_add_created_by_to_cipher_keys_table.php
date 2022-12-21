<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToCipherKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->index();

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('cipher_type')->index()->change();
            $table->foreign('cipher_type')
                ->references('id')
                ->on('cipher_types');

            $table->unsignedBigInteger('key_type')->index()->change();
            $table->foreign('key_type')
                ->references('id')
                ->on('key_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->dropColumn(['created_by']);
        });
    }
}
