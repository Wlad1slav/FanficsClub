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
        Schema::create('fanfictions', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)
                ->unique()
                ->collation('latin1_bin');

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

            $table->string('title', 255);
            $table->string('image', 255)->nullable();
            $table->text('description')->nullable();
            $table->json('addtional_descriptions')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedBigInteger('views')->default(0);

            $table->string('category', 255)->nullable();
            $table->foreign('category')
                ->references('name')
                ->on('categories')
                ->onDelete('set null');

            $table->string('age_rating', 255)->nullable();
            $table->foreign('age_rating')
                ->references('name')
                ->on('age_ratings')
                ->onDelete('set null');

            $table->string('series', 255)
                ->nullable()
                ->collation('latin1_bin');
            $table->foreign('series')
                ->references('slug')
                ->on('series')
                ->onDelete('set null');

            $table->boolean('is_draft')->default(1);
            $table->boolean('is_frozen')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_crossover')->default(0);
            $table->boolean('is_promotes')->default(0);
            $table->boolean('is_sequel')->default(0);

            $table->unsignedBigInteger('sequel')->nullable();
            $table->foreign('sequel')
                ->references('id')
                ->on('fanfictions')
                ->onDelete('set null');

            $table->json('users_with_access')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fanfictions');
    }
};
