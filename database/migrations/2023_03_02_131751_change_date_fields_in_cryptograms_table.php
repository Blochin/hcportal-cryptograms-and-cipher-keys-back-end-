<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDateFieldsInCryptogramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cryptograms', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('month');
            $table->dropColumn('day');
            $table->dropColumn('flag');

            $table->datetime('date')->nullable();
            $table->string('date_around')->nullable();
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
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->boolean('flag')->default(false);

            $table->dropColumn('date');
            $table->dropColumn('date_around');
        });
    }
}
