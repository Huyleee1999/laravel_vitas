<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ExpertDetailCollection;
use App\Http\Resources\v1\ExpertDetailResource;
use Illuminate\Http\Request;
use App\Models\Expert;

class ExpertDetailController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/expert-detail",
*   summary="Get all expert detail",
*   description="Get all expert detail",
*   tags={"Expert detail"},
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
    $expertDetailCollection = new ExpertDetailCollection(Expert::where('status', '1')->where('type', '2')->get());
    if ($expertDetailCollection->count() > 0) {

        return $this->sentSuccessResponse($expertDetailCollection, 'Get all expert successfully!!', 200);
    } else {
        return $this->sentFailureResponse(404, 'Data not found!!');
    }
}


/**
* @OA\Get(
*   path="/api/v1/expert-detail/{id}",
*   summary="Get detail of expert by id",
*   description="Get detail of expert by id",
*   tags={"Expert detail"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the expert detail",
*         @OA\Schema(
*             type="integer"
*         )
*   ),
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
    public function show($id)
    {
        $expertDetail = Expert::find($id);
        if($expertDetail) {
            $expertDetailResource = new ExpertDetailResource($expertDetail);
            return $this->sentSuccessResponse($expertDetailResource, 'Get banner by id successfully!!', 201);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }

}
