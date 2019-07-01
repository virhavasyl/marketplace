<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcatNullFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create stored function
        DB::unprepared("CREATE FUNCTION CONCAT_NULL(str1 VARCHAR(255),str2 VARCHAR(255),str3 VARCHAR(255)) RETURNS TEXT RETURN TRIM(CONCAT(COALESCE(str1,''),COALESCE(str2,''),COALESCE(STR3,''))) COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS CONCAT_NULL");
    }
}
