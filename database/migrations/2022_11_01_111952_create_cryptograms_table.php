<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptogramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cryptograms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_url');
            $table->text('description');
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->string('availability');
            $table->boolean('flag')->default(false);
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('solution_id');
            $table->unsignedBigInteger('state_id')->nullable();

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('sender_id')
                ->references('id')
                ->on('persons')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('recipient_id')
                ->references('id')
                ->on('persons');

            $table->foreign('solution_id')
                ->references('id')
                ->on('solutions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onDelete('set null')
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
        Schema::dropIfExists('ciphers');
    }
}
