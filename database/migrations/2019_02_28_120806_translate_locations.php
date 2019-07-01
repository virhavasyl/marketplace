<?php

use Google\Cloud\Translate\TranslateClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Migrations\Migration;
use App\Models\Location;

class TranslateLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $locations = Location::all();
        $translate = new TranslateClient([
            'projectId' => env("GOOGLE_PROJECT_ID")
        ]);
        try {
            foreach ($locations as $item) {
                $ua_term = $item->translate('uk')->title;
                $en_term = $translate->translate($ua_term, [
                    'target' => 'en',
                    'source' => 'uk'
                ]);
                $item->translate('en')->title = !empty($en_term['text']) ? $en_term['text'] : null;

                $ru_term = $translate->translate($ua_term, [
                    'target' => 'ru',
                    'source' => 'uk'
                ]);
                $item->translate('ru')->title = !empty($ru_term['text']) ? $ru_term['text'] : null;
                $item->save();
            }
        } catch (Exception $exception) {
            Log::error("{$exception->getFile()}({$exception->getLine()})");
            Log::error($exception->getTraceAsString());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
