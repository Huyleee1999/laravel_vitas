<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\v1\CityCollection;
use App\Http\Resources\v1\CityResource;
use App\Models\City;
use OpenApi\Annotations as OA;


class CityController extends Controller
{
/**
* @OA\Get(
*   path="/api/v1/city",
*   summary="Get all City",
*   description="Get all City",
*   tags={"City"},
*   
*   @OA\Response(
*       response = 200,
*       description = "Get Successfully!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 404,
*       description = "Data not found!!",
*       @OA\JsonContent()
*    ),
*)
*/
    public function index()
    {
        $citycollection = new CityCollection(City::all());

        if ($citycollection->count() > 0) {
            return $this->sentSuccessResponse($citycollection, 'Get all city successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }

    
    public function show($id)
    {
        $city = City::find($id);
        if($city) {
            $cityResource = new CityResource($city);
            return $this->sentSuccessResponse($cityResource, 'Get city by id successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }

   
}
