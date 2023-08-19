<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\v1\ProfessionsCollection;
use App\Http\Resources\v1\ProfessionsResource;
use App\Models\Professions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProfessionController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/professions",
*   summary="Get all professions",
*   description="Get all professions",
*   tags={"Professions"},
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
        $professionsCollection = new ProfessionsCollection(Professions::where('status', '1')->get());
        
        if($professionsCollection->count() > 0) {
            return $this->sentSuccessResponse($professionsCollection, 'Get all professions successfully!!', 200);
        } else {
            return $this->sentFailureResponse4(404, 'Data not found!!');
        }
    }


/**
* @OA\Post(
*   path="/api/v1/professions",
*   tags={"Professions"},
*   summary="Create a new professions",
*   description="Create a new professions",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="name", type="string"),
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
*       response = 404,
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
            'name' => 'required',
        ]);
        
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $professions = Professions::create([
                'name' => $request->name,
                'status' => 1
            ]);
            $professionsResource =  new ProfessionsResource($professions);

            if($professionsResource) {
                return $this->sentSuccessResponse($professionsResource, 'Created successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $professions = Professions::where('status', '1')->find($id);
        if($professions) {
            $professionsResource = new ProfessionsResource($professions);
            return $this->sentSuccessResponse($professionsResource, 'Get profession by id successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }


/**
* @OA\Put(
*   path="/api/v1/professions/{id}",
*   tags={"Professions"},
*   summary="Update a new profession",
*   description="Update a new profession",
*   
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="id of the profession",
*         @OA\Schema(
*             type="integer"
*         ),
*   ),
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="name", type="string"),
*               },
*           ),
*       ),
*   ),
*
*   @OA\Response(
*       response = 200,
*       description = "Updated Successfully!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 404,
*       description = "Data not found!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 422,
*       description = "Validated fail!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 500,
*       description = "Something went wrong!!",
*       @OA\JsonContent()
*    ),
*)
*/
    public function update(Request $request, $id)
    {
        $professions = Professions::find($id);
        if($professions) {
            $validator = Validator::make($request->all(), [
                'name' => 'required', 
            ]);
            if($validator->fails()) {
                return response()->json([
                    'status_code' => 422,
                    'errors' => $validator->messages()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $professions->update([
                    'name' => $request->name,
                    'status' => 1
                ]);
                $professionsResource =  new ProfessionsResource($professions);
                
                return $this->sentSuccessResponse($professionsResource, 'Updated successfully!!', 200);
            }
        } else {
            return $this->sentFailureResponse(500, 'Something went wrong!!');
        }
    }


/**
* @OA\Delete(
*   path="/api/v1/professions/{id}",
*   summary="Delete profession by id",
*   description="Delete profession by id",
*   tags={"Professions"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the profession",
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
        $professions = Professions::find($id);

        if($professions) {
            $professions->delete();
            return $this->sentSuccessResponse("", 'Deleted successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
