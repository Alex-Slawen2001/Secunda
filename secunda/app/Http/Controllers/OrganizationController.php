<?php
namespace App\Http\Controllers;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use Illuminate\Http\Request;
class OrganizationController extends Controller
{
    // Все организации в здании
    public function byBuilding($buildingId)
    {
        return Organization::where('building_id', $buildingId)->with(['phones','activities','building'])->get();
    }
    // Организации по деятельности (и вложенным подвидам)
    public function byActivity($activityId)
    {
        // Рекурсивно находим всех потомков до 3 уровня
        $ids = $this->descendantActivityIds($activityId, 1);
        return Organization::whereHas('activities', function($q) use ($ids) {
            $q->whereIn('activity_id', $ids);
        })->with(['phones','activities','building'])->get();
    }
    // Рекурсивный сбор id всех вложенных activities до 3 уровня
    private function descendantActivityIds($id, $level)
    {
        if ($level > 3) return [];
        $ids = [$id];
        foreach (Activity::where('parent_id', $id)->get() as $child) {
            $ids = array_merge($ids, $this->descendantActivityIds($child->id, $level + 1));
        }
        return $ids;
    }
    // Организации по координатам (радиус в км)
    public function byLocation(Request $request)
    {
        $lat = $request->lat; $lng = $request->lng; $radius = $request->radius ?? 1;
        $buildings = Building::selectRaw("id,
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?))
            + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
            ->having("distance", "<=", $radius)->get()->pluck('id');
        return Organization::whereIn('building_id', $buildings)->with(['phones','activities','building'])->get();
    }
    // Поиск по названию
    public function searchByName(Request $request)
    {
        $name = $request->input('name');
        return Organization::where('name', 'like', "%$name%")->with(['phones','activities','building'])->get();
    }
    // Подробно по id
    public function show($id)
    {
        return Organization::with(['phones','activities','building'])->findOrFail($id);
    }
    // Поиск организаций, включая все вложенности заданной деятельности
    public function searchByActivity(Request $request)
    {
        $activityName = $request->input('activity');
        $activity = Activity::where('name', $activityName)->firstOrFail();
        return $this->byActivity($activity->id);
    }
}
