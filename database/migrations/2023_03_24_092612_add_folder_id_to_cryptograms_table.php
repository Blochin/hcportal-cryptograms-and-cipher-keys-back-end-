<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFolderIdToCryptogramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cryptograms', function (Blueprint $table) {
            $table->unsignedBigInteger('folder_id')->index()->nullable();

            $table->string('availability')->nullable()->change();

            $table->foreign('folder_id')
                ->references('id')
                ->on('folders')
                ->onDelete('SET NULL');
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
            $table->string('availability')->change();
            $table->dropColumn(['folder_id']);
        });
    }
}
