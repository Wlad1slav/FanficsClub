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
        Schema::table('tag_categories', function (Blueprint $table) {
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags_tables', function (Blueprint $table) {
            //
        });
    }
};
