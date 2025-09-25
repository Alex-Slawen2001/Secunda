<?php
namespace App\Swagger;
/**
 * @OA\Info(
 *     title="API организаций и зданий",
 *     version="1.0.0",
 *     description="API для работы с организациями, зданиями и видами деятельности"
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Локальный сервер"
 * )
 */

/**
 * @OA\Tag(
 *     name="Organizations",
 *     description="Работа с организациями"
 * )
 */

/**
 * @OA\Tag(
 *     name="Buildings",
 *     description="Работа со зданичными данными"
 * )
 */

/**
 * @OA\Schema(
 *   schema="Organization",
 *   type="object",
 *   required={"id", "name", "building_id"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="ООО Рога и Копыта"),
 *   @OA\Property(property="building_id", type="integer", example=1),
 *   @OA\Property(
 *     property="phones",
 *     type="array",
 *     description="Массив телефонов организации",
 *     @OA\Items(ref="#/components/schemas/OrganizationPhone")
 *   ),
 *   @OA\Property(
 *     property="activities",
 *     type="array",
 *     description="Виды деятельности организации",
 *     @OA\Items(ref="#/components/schemas/Activity")
 *   ),
 *   @OA\Property(property="building", ref="#/components/schemas/Building"),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-24T12:00:00.000000Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-24T12:00:00.000000Z")
 * )
 */

/**
 * @OA\Schema(
 *   schema="OrganizationPhone",
 *   type="object",
 *   required={"id","organization_id","phone"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="organization_id", type="integer", example=1),
 *   @OA\Property(property="phone", type="string", example="8-923-666-13-13"),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-24T12:00:00.000000Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-24T12:00:00.000000Z")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Building",
 *   type="object",
 *   required={"id", "address", "latitude", "longitude"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="address", type="string", example="г. Москва, ул. Ленина 1, офис 3"),
 *   @OA\Property(property="latitude", type="string", example="55.755826"),
 *   @OA\Property(property="longitude", type="string", example="37.6172999"),
 *   @OA\Property(
 *     property="organizations",
 *     type="array",
 *     description="Организации в данном здании",
 *     @OA\Items(ref="#/components/schemas/Organization")
 *   ),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-24T12:00:00.000000Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-24T12:00:00.000000Z")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Activity",
 *   type="object",
 *   required={"id", "name"},
 *   @OA\Property(property="id", type="integer", example=3),
 *   @OA\Property(property="name", type="string", example="Молочная продукция"),
 *   @OA\Property(property="parent_id", type="integer", nullable=true, example=1, description="ID родительской деятельности, если есть"),
 *   @OA\Property(property="level", type="integer", example=2, description="Уровень вложенности"),
 *   @OA\Property(
 *     property="children",
 *     type="array",
 *     description="Дочерние виды деятельности",
 *     @OA\Items(ref="#/components/schemas/Activity")
 *   ),
 *   @OA\Property(
 *     property="organizations",
 *     type="array",
 *     description="Организации по этому виду деятельности",
 *     @OA\Items(ref="#/components/schemas/Organization")
 *   ),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-24T12:00:00.000000Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-24T12:00:00.000000Z")
 * )
 */

/**
 * @OA\Get(
 *     path="/organizations/building/{building}",
 *     summary="Список организаций в конкретном здании",
 *     tags={"Organizations"},
 *     security={{"apikey":{}}},
 *     @OA\Parameter(
 *         name="building",
 *         in="path",
 *         required=true,
 *         description="ID здания",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Список организаций",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
 *     ),
 *     @OA\Response(response=404, description="Здание не найдено")
 * )
 */

/**
 * @OA\Get(
 *     path="/organizations/activity/{activity}",
 *     summary="Список организаций по виду деятельности",
 *     tags={"Organizations"},
 *     security={{"apikey":{}}},
 *     @OA\Parameter(
 *         name="activity",
 *         in="path",
 *         required=true,
 *         description="ID вида деятельности",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Список организаций",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
 *     ),
 *     @OA\Response(response=404, description="Виды деятельности не найдены")
 * )
 */

/**
 * @OA\Get(
 *     path="/organizations/nearby",
 *     summary="Список организаций по координатам и радиусу",
 *     tags={"Organizations"},
 *     security={{"apikey":{}}},
 *     @OA\Parameter(
 *         name="lat",
 *         in="query",
 *         required=true,
 *         description="Широта центра поиска",
 *         @OA\Schema(type="number", format="float")
 *     ),
 *     @OA\Parameter(
 *         name="lng",
 *         in="query",
 *         required=true,
 *         description="Долгота центра поиска",
 *         @OA\Schema(type="number", format="float")
 *     ),
 *     @OA\Parameter(
 *         name="radius",
 *         in="query",
 *         required=false,
 *         description="Радиус поиска (км, по умолчанию 1)",
 *         @OA\Schema(type="number", format="float")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Список организаций",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
 *     ),
 *     @OA\Response(response=400, description="Некорректные координаты")
 * )
 */

/**
 * @OA\Get(
 *     path="/organizations/search/name",
 *     summary="Поиск организаций по названию",
 *     tags={"Organizations"},
 *     security={{"apikey":{}}},
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         required=true,
 *         description="Название организации",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Список организаций",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
 *     ),
 *     @OA\Response(response=404, description="Организации не найдены")
 * )
 */

/**
 * @OA\Get(
 *     path="/organizations/{id}",
 *     summary="Информация об организации по ID",
 *     tags={"Organizations"},
 *     security={{"apikey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID организации",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Данные организации",
 *         @OA\JsonContent(ref="#/components/schemas/Organization")
 *     ),
 *     @OA\Response(response=404, description="Организация не найдена")
 * )
 */

/**
 * @OA\Get(
 *     path="/organizations/search/activity",
 *     summary="Поиск организаций по виду деятельности",
 *     tags={"Organizations"},
 *     security={{"apikey":{}}},
 *     @OA\Parameter(
 *         name="activity",
 *         in="query",
 *         required=true,
 *         description="Название вида деятельности",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Список организаций",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
 *     ),
 *     @OA\Response(response=404, description="Организации не найдены")
 * )
 */

/**
 * @OA\Get(
 *     path="/buildings",
 *     summary="Список всех зданий",
 *     tags={"Buildings"},
 *     security={{"apikey":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Список зданий",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Building"))
 *     ),
 *     @OA\Response(response=404, description="Здания не найдены")
 * )
 */

class SwaggerDocsAll {}
