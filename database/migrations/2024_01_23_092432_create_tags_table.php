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

            $table->string('category', 255)->nullable();
            $table->foreign('category')
                ->references('name')
                ->on('tag_categories')
                ->onDelete('restrict');

            $table->bigInteger('rating')->default(0);
            $table->text('description')->nullable();

            $table->string('belonging_to_fandom', 255)->nullable();
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
