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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('notification', 255)->nullable();

//            $table->string('category', 255)->nullable();
//            $table->foreign('category')
//                ->references('name')
//                ->on('tag_categories')
//                ->onDelete('set null');

            $table->unsignedBigInteger('rating')->default(0);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('belonging_to_fandom_id')->nullable();
            $table->foreign('belonging_to_fandom_id')
                ->references('id')
                ->on('fandoms')
                ->onDelete('cascade');

            $table->unsignedBigInteger('added_by_user')->nullable();
            $table->foreign('added_by_user')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

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
        Schema::dropIfExists('tags');
    }
};
