<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLocationsTable
 */
class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('locations')->onDelete('cascade');
        });

        // Translations
        Schema::create('location_translations', function (Blueprint $table) {
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('title');
            $table->string('locale', 2)->index();
            $table->primary(['location_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_translations');
        Schema::dropIfExists('locations');
    }
}
