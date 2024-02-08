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

            $table->json('fandoms_id')->nullable();

            $table->string('title', 255);
            $table->string('image', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('additional_descriptions')->nullable();
            $table->json('tags')->nullable();
            $table->json('characters')->nullable();

            $table->unsignedBigInteger('words_amount')->default(0);
            $table->unsignedBigInteger('chapters_amount')->default(0);

            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('rating')->default(0);
            $table->bigInteger('anti_rating')->default(0);

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');

            $table->unsignedBigInteger('age_rating_id');
            $table->foreign('age_rating_id')
                ->references('id')
                ->on('age_ratings')
                ->onDelete('restrict');

            $table->boolean('is_draft')->default(1);
            $table->boolean('is_frozen')->default(0);
            $table->boolean('is_completed')->default(0);
            // $table->boolean('is_crossover')->default(0);
            $table->boolean('is_promotes')->default(0);
            // $table->boolean('is_sequel')->default(0);
            $table->boolean('is_postponed')->default(0);

            $table->unsignedBigInteger('series_id')->nullable();
            $table->foreign('series_id')
                ->references('id')
                ->on('series')
                ->onDelete('set null');

            $table->unsignedBigInteger('sequel_id')->nullable();
            $table->foreign('sequel_id')
                ->references('id')
                ->on('fanfictions')
                ->onDelete('set null');

            $table->unsignedBigInteger('prequel_id')->nullable();
            $table->foreign('prequel_id')
                ->references('id')
                ->on('fanfictions')
                ->onDelete('set null');

            $table->boolean('is_translate')->default(0);
            $table->boolean('is_robot_translate')->default(0);
            $table->string('original_author')->nullable();
            $table->string('original_url')->nullable();

            $table->json('users_with_access')->nullable();

            $table->json('chapters_sequence')->nullable();

            $table->timestamps();

            $table->boolean('is_banned')->default(0);

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
