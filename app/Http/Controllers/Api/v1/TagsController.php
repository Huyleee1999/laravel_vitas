<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TagsCollection;
use App\Http\Resources\v1\TagsResource;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class TagsController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/tags",
*   summary="Get all tags",
*   description="Get all tags",
*   tags={"Tags"},
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
        $tagsCollection = new TagsCollection(Tags::where('status', '1')->get());
        
        if($tagsCollection->count() > 0) {
            return $this->sentSuccessResponse($tagsCollection, 'Get all tags successfully!!', 200);
        } else {
            return $this->sentFailureResponse4(404, 'Data not found!!');
        }
    }


/**
* @OA\Post(
*   path="/api/v1/tags",
*   tags={"Tags"},
*   summary="Create a new tag",
*   description="Create a new tag",
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
        ]);
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $tags = Tags::create([
                'name' => $request->name,
                'status' => 1
            ]);
            $tagsResource = new TagsResource($tags);

            if($tagsResource) {
                return $this->sentSuccessResponse($tagsResource, 'Created successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }

/**
* @OA\Get(
*   path="/api/v1/tags/{id}",
*   summary="Get tag by id",
*   description="Get tag by id",
*   tags={"Tags"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the tag",
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
    public function show(string $id)
    {
        $tags = Tags::where('status', '1')->find($id);
        if($tags) {
            $tagsResource = new TagsResource($tags);
            return $this->sentSuccessResponse($tagsResource, 'Get question by id successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

 /**
* @OA\Delete(
*   path="/api/v1/tags/{id}",
*   summary="Delete tag by id",
*   description="Delete tag by id",
*   tags={"Tags"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the tag",
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
        $tags = Tags::find($id);

        if($tags) {
            $tags->delete();
            return $this->sentSuccessResponse("", 'Deleted successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
