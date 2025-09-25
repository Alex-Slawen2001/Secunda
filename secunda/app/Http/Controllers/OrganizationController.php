<?php
namespace App\Http\Controllers;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use Illuminate\Http\Request;
class OrganizationController extends Controller
{
    public function byBuilding($buildingId)
    {
        $organizations = Organization::where('building_id', $buildingId)->with(['phones', 'activities', 'building'])->get();
        return response()->json($organizations, 200, ['Content-Type' => 'application/json']);
    }

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

    public function searchByName(Request $request)
    {
        $name = $request->input('name');
        $organizations = Organization::where('name', 'like', "%$name%")->with(['phones', 'activities', 'building'])->get();
        return response()->json($organizations, 200, ['Content-Type' => 'application/json']);
    }

    public function show($id)
    {
        $organization = Organization::with(['phones', 'activities', 'building'])->findOrFail($id);
        return response()->json($organization, 200, ['Content-Type' => 'application/json']);
    }

    public function searchByActivity(Request $request)
    {
        $activityName = $request->input('activity');
        $activity = Activity::where('name', $activityName)->firstOrFail();
        $organizations = $this->byActivity($activity->id);
        return response()->json($organizations, 200, ['Content-Type' => 'application/json']);
    }
}
