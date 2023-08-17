<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\FeatureProgramsCollection;
use App\Http\Resources\v1\FeatureProgramsResource;
use App\Models\FeaturePrograms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class FeatureProgramsController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/featureprograms",
*   summary="Get all featureprograms",
*   description="Get all featureprograms",
*   tags={"Programs"},
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
        $featureprogramscollection = new FeatureProgramsCollection(FeaturePrograms::where('status', '1')->get());
        
        if($featureprogramscollection->count() > 0) {
            return $this->sentSuccessResponse($featureprogramscollection, 'Get all programs successfully!!', 201);
        } else {
            return $this->sentFailureResponse4(404, 'Data not found!!');
        }
    }


/**
* @OA\Post(
*   path="/api/v1/featureprograms",
*   tags={"Programs"},
*   summary="Create a new featureprogram",
*   description="Create a new featureprogram",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="name", type="string"),
*               @OA\Property(property="description", type="string"),
*               @OA\Property(property="content", type="string"),
*               @OA\Property(property="profession_id", type="integer"),
*               },
*           ),
*       ),
*   ),
*
*   @OA\Response(
*       response = 201,
*       description = "Updated Successfully!!",
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'profession_id' => 'required',
            'content' => 'required',
        ]);
        
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $programs = FeaturePrograms::create([
                'name' => $request->name,
                'description' => $request->description,
                'content' => $request->content,
                'profession_id' => $request->profession_id,
                'status' => $request->status == true ? '1' : '0',
            ]);
            $featureProgramsResource =  new FeatureProgramsResource($programs);

            if($featureProgramsResource) {
                return $this->sentSuccessResponse($featureProgramsResource, 'Created successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $programs = FeaturePrograms::where('status', '1')->find($id);
        if($programs) {
            $featureProgramsResource = new FeatureProgramsResource($programs);
            return $this->sentSuccessResponse($featureProgramsResource, 'Get program by id successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }


/**
* @OA\Put(
*   path="/api/v1/featureprograms/{id}",
*   tags={"Programs"},
*   summary="Update a new program",
*   description="Update a new program",
*   
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="id of the program",
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
*               @OA\Property(property="content", type="string"),
*               @OA\Property(property="profession_id", type="integer"),
*               @OA\Property(property="description", type="string"),
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
    public function update(Request $request, string $id)
    {
        $programs = FeaturePrograms::find($id);
        if($programs) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'profession_id' => 'required',
                'content' => 'required',
            ]);
            if($validator->fails()) {
                return response()->json([
                    'status_code' => 422,
                    'errors' => $validator->messages()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else { 
                $programs = FeaturePrograms::find($id);
                $programs->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'content' => $request->content,
                    'profession_id' => $request->profession_id,
                    'status' => 1
                ]);
                $featureProgramsResource =  new FeatureProgramsResource($programs);

                return $this->sentSuccessResponse($featureProgramsResource, 'Updated successfully!!', 200);
            }
        } else {
            return $this->sentFailureResponse(500, 'Something went wrong!!');
        }
    }


/**
* @OA\Delete(
*   path="/api/v1/featureprograms/{id}",
*   summary="Delete program by id",
*   description="Delete program by id",
*   tags={"Programs"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the program",
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
        $programs = FeaturePrograms::find($id);

        if($programs) {
            $programs->delete();
            return $this->sentSuccessResponse("", 'Deleted successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
