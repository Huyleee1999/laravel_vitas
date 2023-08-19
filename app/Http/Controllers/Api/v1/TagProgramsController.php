<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TagProgramsCollection;
use App\Http\Resources\v1\TagProgramsResource;
use App\Models\TagPrograms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class TagProgramsController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/tagprograms",
*   summary="Get all tagprograms",
*   description="Get all tagprograms",
*   tags={"TagPrograms"},
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
        $tagprogramsCollection = new TagProgramsCollection(TagPrograms::all());
        
        if($tagprogramsCollection->count() > 0) {
            return $this->sentSuccessResponse($tagprogramsCollection, 'Get all tags successfully!!', 200);
        } else {
            return $this->sentFailureResponse4(404, 'Data not found!!');
        }
    }


/**
* @OA\Post(
*   path="/api/v1/tagprograms",
*   tags={"TagPrograms"},
*   summary="Create a new tagprogram",
*   description="Create a new tagprogram",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="tag_id", type="integer"),
*               @OA\Property(property="program_id", type="integer"),
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
*       description = "Validated fails!!",
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
            'tag_id' => 'required',
            'program_id' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $tagprograms = TagPrograms::create([
                'tag_id' => $request->tag_id,
                'program_id' => $request->program_id,
            ]);
            $tagprogramsresource =  new TagProgramsResource($tagprograms);

            if($tagprogramsresource) {
                return $this->sentSuccessResponse($tagprogramsresource, 'Created successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }

/**
* @OA\Put(
*   path="/api/v1/tagprograms/{id}",
*   tags={"TagPrograms"},
*   summary="Update a new tagprogram",
*   description="Update a new tagprogram",
*   
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="id of the tagprogram",
*         @OA\Schema(
*             type="integer"
*         ),
*   ),
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="tag_id", type="integer"),
*               @OA\Property(property="program_id", type="integer"),
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
*       response = 422,
*       description = "Validated fail!!",
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
    public function update(Request $request, string $id)
    {
        $tagprograms = TagPrograms::find($id);
        if($tagprograms) {
            $validator = Validator::make($request->all(), [
                'tag_id' => 'required', 
                'program_id' => 'required', 
            ]);
            if($validator->fails()) {
                return response()->json([
                    'status_code' => 422,
                    'errors' => $validator->messages()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $tagprograms->update([
                    'tag_id' => $request->tag_id,
                    'program_id' => $request->program_id,                   
                ]);
                $tagprogramsResource =  new TagProgramsResource($tagprograms);
                
                return $this->sentSuccessResponse($tagprogramsResource, 'Updated successfully!!', 200);
            }
        } else {
            return $this->sentFailureResponse(500, 'Something went wrong!!');
        }
    }

/**
* @OA\Delete(
*   path="/api/v1/tagprograms/{id}",
*   summary="Delete tagprogram by id",
*   description="Delete tagprogram by id",
*   tags={"TagPrograms"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the tagprogram",
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
        $tagprograms = TagPrograms::find($id);

        if($tagprograms) {
            $tagprograms->delete();
            return $this->sentSuccessResponse("", 'Deleted successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
