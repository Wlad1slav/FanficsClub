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

            $table->string('fandom_category', 255)
                ->collation('latin1_bin');
            $table->foreign('fandom_category')
                ->references('slug')
                ->on('fandom_categories')
                ->onDelete('cascade');

            $table->text('description')->nullable();

            $table->timestamps();
        });

        Schema::table('fandoms', function (Blueprint $table) {
            $table->string('related_media_giant', 255)
                ->collation('latin1_bin')
                ->nullable();
            $table->foreign('related_media_giant')
                ->references('slug')
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
