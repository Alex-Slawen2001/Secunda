<?php
namespace Database\Seeders;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use Illuminate\Database\Seeder;
class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $o1 = Organization::create([
            'name' => 'ООО "Рога и Копыта"',
            'building_id' => 1,
        ]);
        $o1->phones()->createMany([
            ['phone' => '2-222-222'],
            ['phone' => '8-923-666-13-13'],
        ]);
        $o1->activities()->attach([3,4]); // Мясная продукция, Молочная продукция
        $o2 = Organization::create([
            'name' => 'ООО "Колеса"',
            'building_id' => 2,
        ]);
        $o2->phones()->create(['phone' => '3-333-333']);
        $o2->activities()->attach([5,6,7]); // Грузовые, Легковые, Запчасти
    }
}
