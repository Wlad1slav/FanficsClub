<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('chapter_id');
            $table->foreign('chapter_id')
                ->references('id')
                ->on('chapters')
                ->onDelete('cascade');

            $table->unsignedBigInteger('answer_to_review')->nullable();
            $table->foreign('answer_to_review')
                ->references('id')
                ->on('reviews')
                ->onDelete('cascade');

            $table->unsignedBigInteger('answer_to_user')->nullable();
            $table->foreign('answer_to_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->text('content');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
