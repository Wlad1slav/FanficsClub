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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('fanfiction_id');
            $table->foreign('fanfiction_id')
                ->references('id')
                ->on('fanfictions')
                ->onDelete('cascade');

            $table->string('title', 255);
            $table->string('slug', 255)
                ->collation('latin1_bin')
                ->unique();
            $table->longText('content');
            $table->json('additional_descriptions')->nullable();
            // $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_draft')->default(1);

            $table->dateTime('published')->nullable();

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
        Schema::dropIfExists('chapters');
    }
};
