<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ExpertCollection;
use App\Models\Expert;
use Illuminate\Http\Request;
use App\Http\Resources\v1\ExpertResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class ExpertController extends Controller
{
/**
* @OA\Get(
*   path="/api/v1/expert",
*   summary="Get all experts",
*   description="Get all experts",
*   tags={"Experts"},
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
        $expertCollection = new ExpertCollection(Expert::where('status', '1')->where('type', '2')->get());
        if ($expertCollection->count() > 0) {

            return $this->sentSuccessResponse($expertCollection, 'Get all expert successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required', 
    //         'description' => 'required', 
    //         'position' => 'required', 
    //     ]);
        
    //     if($validator->fails()) {
    //         return response()->json([
    //             'status_code' => 422,
    //             'errors' => $validator->messages()
    //         ], Response::HTTP_UNPROCESSABLE_ENTITY);
    //     } else {
    //         $expert = Expert::create([
    //             'name' => $request->name,
    //             'description' => $request->description, 
    //             'position' => $request->position,
    //             'status' => $request->status == true ? '1' : '0',
    //             'type' => $request->type
    //         ]);
    //         $expertresource =  new ExpertResource($expert);

    //         if($expertresource) {
    //             return $this->sentSuccessResponse($expertresource);
    //         } else {
    //             // return response()->json([
    //             //     'message' => 'Something went wrong!',
    //             //     'status_code' => 500
    //             // ], Response:: HTTP_INTERNAL_SERVER_ERROR);
    //             return $this->sentFailureResponse(500, 'Something went wrong!!');
    //         }
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $expert = Expert::where('status', '1')->where('type', '2')->find($id);
        if($expert) {
            $expertresource = new ExpertResource($expert);
            return $this->sentSuccessResponse($expertresource, 'Get expert by id successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }


/**
* @OA\Put(
*   path="/api/v1/expert/{id}",
*   tags={"Experts"},
*   summary="Update a new expert",
*   description="Update a new expert",
*   
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="id of the expert",
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
*               @OA\Property(property="position", type="string"),
*               @OA\Property(property="description", type="string"),
*               @OA\Property(property="type", type="integer"),
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
        $expert = Expert::find($id);
        if($expert) {
            $validator = Validator::make($request->all(), [
                'name' => 'required', 
                'description' => 'required', 
                'position' => 'required', 
                'type' => 'required', 
            ]);
            if($validator->fails()) {
                return response()->json([
                    'status_code' => 422,
                    'errors' => $validator->messages()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $expert->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'position' => $request->position,
                    'status' => 1,
                    'type' => $request->type
                ]);
                $expertresource =  new ExpertResource($expert);
                
                return $this->sentSuccessResponse($expertresource, 'Updated successfully!!', 201);
            }
        } else {
            return $this->sentFailureResponse(500, 'Something went wrong!!');
        }
    }

/**
* @OA\Delete(
*   path="/api/v1/expert/{id}",
*   summary="Delete expert by id",
*   description="Delete expert by id",
*   tags={"Experts"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the expert",
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
    public function destroy($id)
    {
        $expert = Expert::find($id);

        if($expert) {
            $expert->delete();
            return $this->sentSuccessResponse("", 'Deleted successfully!!', 200);
        } else {
           return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
