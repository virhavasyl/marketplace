<?php

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldLocations = DB::table('locationss')
            ->where('status', 'active')
            ->whereNotIn('type', ['район міста'])
            ->orderBy('id', 'asc')
            ->get();

        foreach ($oldLocations as $oldLocation) {
            if ($oldLocation->type == 'область') {
                $type = Location::TYPE_REGION;
                $title = $oldLocation->id == 1 ? $oldLocation->name : $oldLocation->name . ' область';
            } elseif ($oldLocation->type == 'район області') {
                $type = Location::TYPE_DISTRICT;
                $title = $oldLocation->name . ' район';
            } else {
                $type = Location::TYPE_LOCALITY;
                $title = $oldLocation->name;
            }

            $data = [
                'type' => $type,
                'parent_id' => $oldLocation->parent_id,
                'uk' => [
                    'title' => $title
                ],
                'ru' => [
                    'title' => $title
                ],
                'en' => [
                    'title' => $title
                ]
            ];

            Location::create($data);
        }
    }
}
