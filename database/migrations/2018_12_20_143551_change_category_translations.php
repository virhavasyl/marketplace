<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCategoryTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropUnique(['category_id', 'locale']);
            $table->dropColumn('id');
            $table->primary(['category_id', 'locale']);
            $table->text('description')->nullable()->after('title');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropPrimary(['category_id', 'locale']);
        });
        Schema::table('category_translations', function (Blueprint $table) {
            $table->increments('id')->first();
            $table->dropColumn('description');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique(['category_id', 'locale']);
        });
    }
}
