<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatetimeToDateInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->date('used_from')->nullable()->change();
            $table->date('used_to')->nullable()->change();
        });

        Schema::table('cryptograms', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
        });

        Schema::table('archives', function (Blueprint $table) {
            $table->dropColumn('short_name');
            $table->dropColumn('country');
        });

        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });

        Schema::table('data', function (Blueprint $table) {
            $table->renamecolumn('blobb', 'blob');
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
            $table->datetime('used_from')->nullable()->change();
            $table->datetime('used_to')->nullable()->change();
        });

        Schema::table('cryptograms', function (Blueprint $table) {
            $table->datetime('date')->nullable()->change();
        });
    }
}
