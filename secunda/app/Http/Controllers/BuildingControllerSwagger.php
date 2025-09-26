<?php

namespace App\Http\Controllers;

use App\Models\Building;

/**
 * @OA\Tag(
 *     name="Buildings",
 *     description="Операции со зданиями"
 * )
 */
class BuildingControllerSwagger extends Controller
{
    /**
     * @OA\Get(
     *     path="/buildings",
     *     summary="Список всех зданий с организациями",
     *     tags={"Buildings"},
     *     @OA\Response(
     *         response=200,
     *         description="Список зданий",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Building"))
     *     )
     * )
     */
    public function index()
    {
        $buildings = Building::with('organizations')->get();
        return response()->json($buildings, 200, ['Content-Type' => 'application/json']);
    }
}
