<?php
namespace Database\Seeders;
use App\Models\Activity;
use Illuminate\Database\Seeder;
class ActivitySeeder extends Seeder
{
public function run()
{
$food = Activity::create(['name' => 'Еда', 'level' => 1]);
$meat = Activity::create(['name' => 'Мясная продукция', 'parent_id' => $food->id, 'level' => 2]);
$milk = Activity::create(['name' => 'Молочная продукция', 'parent_id' => $food->id, 'level' => 2]);
$auto = Activity::create(['name' => 'Автомобили', 'level' => 1]);
$truck = Activity::create(['name' => 'Грузовые', 'parent_id' => $auto->id, 'level' => 2]);
$car = Activity::create(['name' => 'Легковые', 'parent_id' => $auto->id, 'level' => 2]);
$parts = Activity::create(['name' => 'Запчасти', 'parent_id' => $car->id, 'level' => 3]);
$accs = Activity::create(['name' => 'Аксессуары', 'parent_id' => $car->id, 'level' => 3]);
}
}
