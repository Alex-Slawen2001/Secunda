<?php

namespace App\Http\Controllers;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Organizations",
 *     description="Операции с организациями"
 * )
 */
class OrganizationControllerSwagger extends Controller
{
    /**
     * @OA\Get(
     *     path="/organizations/building/{buildingId}",
     *     summary="Получить организации по ID здания",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         description="ID здания",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */
    public function byBuilding($buildingId)
    {
        $organizations = Organization::where('building_id', $buildingId)
            ->with(['phones', 'activities', 'building'])
            ->get();
        return response()->json($organizations, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @OA\Get(
     *     path="/organizations/activity/{activityId}",
     *     summary="Получить организации по ID активности (с учетом потомков)",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         description="ID активности",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */
    public function byActivity($activityId)
    {
        $ids = $this->descendantActivityIds($activityId, 1);
        $organizations = Organization::whereHas('activities', function ($q) use ($ids) {
            $q->whereIn('activity_id', $ids);
        })->with(['phones', 'activities', 'building'])->get();

        return response()->json($organizations, 200, ['Content-Type' => 'application/json']);
    }

    private function descendantActivityIds($id, $level)
    {
        if ($level > 3) return [];
        $ids = [$id];
        foreach (Activity::where('parent_id', $id)->get() as $child) {
            $ids = array_merge($ids, $this->descendantActivityIds($child->id, $level + 1));
        }
        return $ids;
    }

    /**
     * @OA\Get(
     *     path="/organizations/location",
     *     summary="Получить организации по координатам",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="Широта",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         description="Долгота",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Радиус поиска (км, по умолчанию 1)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Не переданы lat/lng"
     *     )
     * )
     */
    public function byLocation(Request $request)
    {
        if (!$request->lat || !$request->lng) {
            return response()->json(['error' => 'Latitude and longitude are required'], 400);
        }

        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius ?? 1;

        $buildings = Building::selectRaw("id,
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?))
            + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
            ->having("distance", "<=", $radius)->get()->pluck('id');

        $organizations = Organization::whereIn('building_id', $buildings)
            ->with(['phones', 'activities', 'building'])
            ->get();

        return response()->json($organizations, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @OA\Get(
     *     path="/organizations/search",
     *     summary="Поиск организаций по названию",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Часть названия организации",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список найденных организаций",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */
    public function searchByName(Request $request)
    {
        $name = $request->input('name');
        $organizations = Organization::where('name', 'like', "%$name%")
            ->with(['phones', 'activities', 'building'])
            ->get();

        return response()->json($organizations, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @OA\Get(
     *     path="/organizations/{id}",
     *     summary="Получить организацию по ID",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID организации",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Данные организации",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена"
     *     )
     * )
     */
    public function show($id)
    {
        $organization = Organization::with(['phones', 'activities', 'building'])->findOrFail($id);

        return response()->json($organization, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @OA\Get(
     *     path="/organizations/search-by-activity",
     *     summary="Поиск организаций по названию активности",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activity",
     *         in="query",
     *         description="Название активности",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список найденных организаций",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */
    public function searchByActivity(Request $request)
    {
        $activityName = $request->input('activity');
        $activity = Activity::where('name', $activityName)->firstOrFail();
        // Здесь корректно возвращается JSON, а не результат метода
        $ids = $this->descendantActivityIds($activity->id, 1);
        $organizations = Organization::whereHas('activities', function ($q) use ($ids) {
            $q->whereIn('activity_id', $ids);
        })->with(['phones', 'activities', 'building'])->get();

        return response()->json($organizations, 200, ['Content-Type' => 'application/json']);
    }
}
