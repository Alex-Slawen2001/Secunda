<?php
namespace Database\Seeders;
use App\Models\Building;
use Illuminate\Database\Seeder;
class BuildingSeeder extends Seeder
{
    public function run()
    {
        Building::create([
            'address' => 'г. Москва, ул. Ленина 1, офис 3',
            'latitude' => 55.755826,
            'longitude' => 37.6173,
        ]);
        Building::create([
            'address' => 'г. Новосибирск, ул. Блюхера 32/1',
            'latitude' => 55.033333,
            'longitude' => 82.916667,
        ]);
    }
}
