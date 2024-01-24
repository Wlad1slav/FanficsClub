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
        Schema::create('series', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('fandom', 255)
                ->nullable()
                ->collation('latin1_bin');
            $table->foreign('fandom')
                ->references('slug')
                ->on('fandoms')
                ->onDelete('cascade');

            $table->string('slug', 255)
                ->collation('latin1_bin')
                ->unique();

            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->json('additional_descriptions')->nullable();
            $table->json('tags')->nullable();

            $table->boolean('is_frozen')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_crossover')->default(0);

            $table->json('crossover')->nullable();

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
        Schema::dropIfExists('series');
    }
};
