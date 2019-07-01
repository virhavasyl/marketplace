<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // Translations
        Schema::create('product_attribute_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_attribute_id')->unsigned();
            $table->foreign('product_attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->string('title');
            $table->string('locale', 2)->index();
            $table->unique(['product_attribute_id','locale'], 'pat_product_attribute_id_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_translations');
        Schema::dropIfExists('product_attributes');
    }
}
