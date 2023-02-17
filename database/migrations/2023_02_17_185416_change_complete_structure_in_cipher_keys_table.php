<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCompleteStructureInCipherKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->text('complete_structure')->nullable()->change();
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
            $table->text('complete_structure')->nullable()->change();
        });
    }
}
