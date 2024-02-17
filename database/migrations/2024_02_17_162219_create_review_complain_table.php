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
        Schema::create('review_complains', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_accuses')->nullable();
            $table->foreign('user_accuses')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('review_id')->nullable();
            $table->foreign('review_id')
                ->references('id')
                ->on('reviews')
                ->onDelete('cascade');

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
        Schema::dropIfExists('review_complain');
    }
};
