<?php
namespace App\Http\Controllers;
use App\Models\Building;
class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::with('organizations')->get();
        return response()->json($buildings, 200, ['Content-Type' => 'application/json']);
    }
}
