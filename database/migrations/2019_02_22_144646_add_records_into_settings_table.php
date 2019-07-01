<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

class AddRecordsIntoSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::create([
            'category_id' => Setting::CATEGORY_SOCIAL,
            'key' => 'facebook_link',
            'value' => 'https://www.facebook.com/',
        ]);

        Setting::create([
            'category_id' => Setting::CATEGORY_SOCIAL,
            'key' => 'instagram_link',
            'value' => 'https://www.instagram.com/',
        ]);

        Setting::create([
            'category_id' => Setting::CATEGORY_SOCIAL,
            'key' => 'youtube_link',
            'value' => 'https://www.youtube.com/',
        ]);

        Setting::create([
            'category_id' => Setting::CATEGORY_SOCIAL,
            'key' => 'twitter_link',
            'value' => 'https://twitter.com/',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('key', 'facebook_link')->delete();
        Setting::where('key', 'instagram_link')->delete();
        Setting::where('key', 'youtube_link')->delete();
        Setting::where('key', 'twitter_link')->delete();
    }
}
