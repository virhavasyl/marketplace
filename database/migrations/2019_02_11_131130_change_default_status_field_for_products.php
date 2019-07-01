<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeDefaultStatusFieldForProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("ALTER TABLE products MODIFY COLUMN status INT(1) NOT NULL DEFAULT '0'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("ALTER TABLE products MODIFY COLUMN status INT(1) NOT NULL DEFAULT '1'");
    }
}
