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

            $table->json('request');

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
