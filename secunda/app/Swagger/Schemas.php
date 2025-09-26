<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="Building",
 *     type="object",
 *     title="Здание",
 *     description="Модель здания",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="address", type="string", example="ул. Примерная, 123"),
 *     @OA\Property(property="latitude", type="number", format="float", example=55.7558),
 *     @OA\Property(property="longitude", type="number", format="float", example=37.6176),
 *     @OA\Property(
 *         property="organizations",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Organization"),
 *         description="Список организаций в здании"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Organization",
 *     type="object",
 *     title="Организация",
 *     description="Модель организации",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Примерная Организация"),
 *     @OA\Property(property="building_id", type="integer", example=1),
 *     @OA\Property(
 *         property="building",
 *         ref="#/components/schemas/Building",
 *         description="Здание, в котором находится организация"
 *     ),
 *     @OA\Property(
 *         property="phones",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OrganizationPhone"),
 *         description="Список телефонов организации"
 *     ),
 *     @OA\Property(
 *         property="activities",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Activity"),
 *         description="Список активностей организации"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="OrganizationPhone",
 *     type="object",
 *     title="Телефон организации",
 *     description="Модель телефона организации",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="organization_id", type="integer", example=1),
 *     @OA\Property(property="phone", type="string", example="+7 999 123-45-67"),
 *     @OA\Property(
 *         property="organization",
 *         ref="#/components/schemas/Organization",
 *         description="Организация, к которой принадлежит телефон"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Activity",
 *     type="object",
 *     title="Активность",
 *     description="Модель активности",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Торговля"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(property="level", type="integer", example=1),
 *     @OA\Property(
 *         property="parent",
 *         ref="#/components/schemas/Activity",
 *         description="Родительская активность"
 *     ),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Activity"),
 *         description="Дочерние активности"
 *     ),
 *     @OA\Property(
 *         property="organizations",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Organization"),
 *         description="Организации, связанные с активностью"
 *     )
 * )
 */
class Schemas {}
