<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\BlogsCollection;
use App\Models\Blogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogsController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/blogs",
*   summary="Get all blogs",
*   description="Get all blogs",
*   tags={"Blogs"},
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
        $blogscollection = new BlogsCollection(Blogs::where('status', '1')->get());
        if($blogscollection->count() > 0) {
            return $this->sentSuccessResponse($blogscollection, 'Get all programs successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }

    }

/**
* @OA\Delete(
*   path="/api/v1/blogs/{id}",
*   summary="Delete blog by id",
*   description="Delete blog by id",
*   tags={"Blogs"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the blog",
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
        $blogs = Blogs::find($id);

        if($blogs) {
            $blogs->delete();
            return $this->sentSuccessResponse("", 'Deleted successfully!!', 200);
        } else {
        return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
