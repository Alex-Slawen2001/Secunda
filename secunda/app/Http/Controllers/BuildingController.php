<?php
namespace App\Http\Controllers;
use App\Models\Building;
class BuildingController extends Controller
{
    public function index()
    {
        return Building::with('organizations')->get();
    }
}
