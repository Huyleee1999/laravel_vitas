<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\v1\BannersResource;
use  App\Models\Banners;
use App\Http\Requests\v1\BannersFormRequest;
use Illuminate\Http\Response;
use App\Http\Resources\v1\BannersCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;


class BannersController extends Controller
{
/**
* @OA\Get(
*   path="/api/v1/banners",
*   summary="Get all banners",
*   description="Get all banners",
*   tags={"Banners"},
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
        $cacheKey = 'banners'; 
        if (Cache::has($cacheKey)) {
            $cachedBanners = Cache::get($cacheKey);
            return $this->sentSuccessResponse($cachedBanners, 'Get all banners successfully!!', 200);
        } else {
            $bannersCollection = new BannersCollection(Banners::where('status', '1')->get());
            if($bannersCollection) {
                Cache::put($cacheKey, $bannersCollection, now()->addMinutes(10));
                return $this->sentSuccessResponse($bannersCollection, 'Get all banners successfully!!', 200);
            } else {
                return $this->sentFailureResponse(404, 'Data not found!!');
            }
        }
    }

/**
* @OA\Post(
*   path="/api/v1/banners",
*   tags={"Banners"},
*   summary="Create a new banner",
*   description="Create a new banner",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="title", type="string"),
*               @OA\Property(property="description", type="string"),
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
            'title' => 'required | string',
            'description' => 'required',
        ]);
        
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $banner = Banners::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status == true ? '1' : '0',
                'type' => 1
            ]);
            $bannersresource =  new BannersResource($banner);

            if($bannersresource) {
                return $this->sentSuccessResponse($bannersresource, 'Created successfully!!', 201);
            } else {
                // return response()->json([
                //     'message' => 'Something went wrong!',
                //     'status_code' => 500
                // ], Response:: HTTP_INTERNAL_SERVER_ERROR);
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }

        // $data = $request->validated();

        // $banner = Banners::create($data);
        // $bannersresource =  new BannersResource($banner);

        // return $this->sentSuccessResponse($bannersresource, 'success', Response::HTTP_OK);
        // return response()->json([
        //     'data' => $bannersresource,
        //     'status_code' => '200'
        // ], Response::HTTP_OK);
    }

/**
* @OA\Get(
*   path="/api/v1/banners/{id}",
*   summary="Get banner by id",
*   description="Get banner by id",
*   tags={"Banners"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the banner",
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
        $banner = Banners::find($id);
        if($banner) {
            $bannersresource = new BannersResource($banner);
            return $this->sentSuccessResponse($bannersresource, 'Get banner by id successfully!!', 201);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
        
        
        // return response()->json([
        //     'data' => $bannersresource,
        //     'status_code' => '200'
        // ], Response::HTTP_OK);
    }


/**
* @OA\Put(
*   path="/api/v1/banners/{id}",
*   tags={"Banners"},
*   summary="Update a new banner",
*   description="Update a new banner",
*   
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="id of the banner",
*         @OA\Schema(
*             type="integer"
*         ),
*   ),
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="title", type="string"),
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
    public function update(Request $request, $id)
    {
        $banner = Banners::find($id);
        if($banner) {
            $validator = Validator::make($request->all(), [
                'title' => 'required | string',
                'description' => 'required',
            ]);
            if($validator->fails()) {
                return response()->json([
                    'status_code' => 422,
                    'errors' => $validator->messages()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else { 
                $banner->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => 1,
                    'type' => $request->type
                ]);
                $bannersresource =  new BannersResource($banner);

                return $this->sentSuccessResponse($bannersresource, 'Updated banner successfully!!', 200);
            }
        } else {
            return $this->sentFailureResponse(500, 'Something went wrong!!');
        }
    }
        // $banner->update($request->all());

        // $bannersresource = new BannersResource($banner);
      
        // return $this->sentSuccessResponse($bannersresource);
        // return response()->json([
        //     'data' => $bannersresource,
        //     'status_code' => '200'
        // ], Response::HTTP_OK);

    // public function update(Request $request, $id)
    // {
    //     $banner = Banners::findOrFail($id);

    //     $dataupdate = $request->all();

    //     $banner->update($dataupdate);

    //     $bannersresource = new BannersResource($banner);

    //     return response()->json([
    //         'data' => $bannersresource,
    //         'status_code' => '200'
    //     ], Response::HTTP_OK);
    // }


/**
* @OA\Delete(
*   path="/api/v1/banners/{id}",
*   summary="Delete banner by id",
*   description="Delete banner by id",
*   tags={"Banners"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the banner",
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
        $banner = Banners::find($id);

        if($banner) {
            $banner->delete();
            return $this->sentSuccessResponse("", 'Deleted successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
