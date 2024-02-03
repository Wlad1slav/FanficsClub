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
        Schema::create('filter_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('made_request')->nullable();
            $table->foreign('made_request')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->json('categories')->nullable();
            $table->json('age_ratings')->nullable();
            $table->json('characters')->nullable();
            $table->json('tags')->nullable();
            $table->json('fandoms_id')->nullable();
            $table->string('sort_by', 255)->nullable();

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
        Schema::dropIfExists('filter_requests');
    }
};
