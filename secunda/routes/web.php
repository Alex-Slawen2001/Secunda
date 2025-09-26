<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\BuildingController;
use Illuminate\Support\Facades\Route;


Route::get('/swagger-docs', function () {
    return view('vendor.l5-swagger.index',[
        'documentationTitle' => config('l5-swagger.documentations.default.title'),
        'useAbsolutePath' => config('l5-swagger.paths.use_absolute_path'),
        'documentation' => 'default',
        'urlsToDocs' => ['default' => 'docs/api-docs.json'],

        ]);
});

Route::middleware('auth.apikey')->group(function() {
    Route::get('/organizations/building/{building}', [OrganizationController::class, 'byBuilding']);
    Route::get('/organizations/activity/{activity}', [OrganizationController::class, 'byActivity']);
    Route::get('/organizations/nearby', [OrganizationController::class, 'byLocation']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
    Route::get('/organizations/search/name', [OrganizationController::class, 'searchByName']);
    Route::get('/organizations/search/activity', [OrganizationController::class, 'searchByActivity']);
    Route::get('/buildings', [BuildingController::class, 'index']);
});

