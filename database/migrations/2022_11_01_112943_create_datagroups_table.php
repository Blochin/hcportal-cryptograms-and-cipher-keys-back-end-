<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatagroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datagroups', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->unsignedBigInteger('cryptogram_id');

            $table->foreign('cryptogram_id')
                ->references('id')
                ->on('cryptograms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datagroups');
    }
}
