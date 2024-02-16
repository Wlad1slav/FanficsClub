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
        Schema::create('fandoms', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)
                ->collation('latin1_bin')
                ->unique();
            $table->string('name', 255)->unique();
            $table->string('image', 255)->nullable();

            $table->unsignedBigInteger('fandom_category_id')->nullable();
            $table->foreign('fandom_category_id')
                ->references('id')
                ->on('fandom_categories')
                ->onDelete('cascade');

            $table->text('description')->nullable();

            $table->unsignedBigInteger('added_by_user')->nullable();
            $table->foreign('added_by_user')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->timestamps();

            $table->bigInteger('fictions_amount')->default(0);
        });

        Schema::table('fandoms', function (Blueprint $table) {
            $table->unsignedBigInteger('related_media_giant_fandom_id')->nullable();
            $table->foreign('related_media_giant_fandom_id')
                ->references('id')
                ->on('fandoms')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fandoms');
    }
};
