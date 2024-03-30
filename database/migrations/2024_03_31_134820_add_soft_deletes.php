<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cryptograms', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cryptograms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
