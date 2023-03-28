<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->renameColumn('signature', 'name');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->dropForeign('cipher_keys_cipher_type_index');
            $table->dropColumn(['cipher_type']);
            $table->unsignedBigInteger('category_id')->index();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');
        });

        Schema::table('cryptograms', function (Blueprint $table) {
            $table->text('used_chars')->nullable();
            $table->renameColumn('image_url', 'thumbnail_url')->nullable();
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
            $table->renameColumn('name', 'signature');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });

        Schema::table('cipher_keys', function (Blueprint $table) {
            $table->dropColumn(['category_id']);
            $table->unsignedBigInteger('cipher_type')->index();
            $table->foreign('cipher_type')
                ->references('id')
                ->on('cipher_types');
        });
    }
}
