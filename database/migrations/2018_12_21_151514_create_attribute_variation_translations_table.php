<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeVariationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_variation_translations', function (Blueprint $table) {
            $table->integer('attribute_variation_id')->unsigned();
            $table->foreign('attribute_variation_id')
                ->references('id')
                ->on('attribute_variations')
                ->onDelete('cascade');
            $table->string('title');
            $table->string('locale', 2)->index();
            $table->primary(['attribute_variation_id','locale'], 'avt_attribute_variation_id_locale_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_variation_translations');
    }
}
