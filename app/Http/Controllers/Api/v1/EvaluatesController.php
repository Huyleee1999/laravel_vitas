<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\EvaluatesCollection;
use App\Http\Resources\v1\EvaluatesResource;
use App\Models\Evaluates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class EvaluatesController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/evaluates",
*   summary="Get all evaluates",
*   description="Get all evaluates",
*   tags={"Evaluates"},
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

        $evaluateCollection = new EvaluatesCollection(Evaluates::where('status', 1)->get());
        
        if ($evaluateCollection->count() > 0) {
            return $this->sentSuccessResponse($evaluateCollection, 'Get all evaluates successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }

/**
* @OA\Post(
*   path="/api/v1/evaluates",
*   tags={"Evaluates"},
*   summary="Create a new evaluate",
*   description="Create a new evaluate",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="content", type="string"),
*               @OA\Property(property="expert_id", type="string"),
*               @OA\Property(property="user_id", type="string"),
*               },
*           ),
*       ),
*   ),
*
*   @OA\Response(
*       response = 201,
*       description = "Created Successfully!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 422,
*       description = "Data not found!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 500,
*       description = "Something went wrong!!",
*       @OA\JsonContent()
*    ),
*)
*/
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'rate' => 'required',
            'expert_id' => 'required',
            'user_id' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $evaluates = Evaluates::create([
                'content' => $request->content,
                'rate' => $request->rate,
                'expert_id' => $request->expert_id,
                'user_id' => $request->user_id,
                'status' => 1
            ]);
            $evaluatesResource =  new EvaluatesResource($evaluates);

            if($evaluatesResource) {
                return $this->sentSuccessResponse($evaluatesResource, 'Created successfully!!', 201);
            } else {
                // return response()->json([
                //     'message' => 'Something went wrong!',
                //     'status_code' => 500
                // ], Response:: HTTP_INTERNAL_SERVER_ERROR);
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }


/**
* @OA\Delete(
*   path="/api/v1/evaluates/{id}",
*   summary="Delete evaluate by id",
*   description="Delete evaluate by id",
*   tags={"Evaluates"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the evaluate",
*         @OA\Schema(
*             type="integer"
*         )
*   ),
*   @OA\Response(
*       response = 200,
*       description = "Delete Successfully!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 404,
*       description = "Data not found!!",
*       @OA\JsonContent()
*    ),
*)
*/
    public function destroy(string $id)
    {
        $evaluates = Evaluates::find($id);

        if($evaluates) {
            $evaluates->delete();
            return $this->sentSuccessResponse("", 'Deleted successfully!!', 200);
        } else {
           return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
