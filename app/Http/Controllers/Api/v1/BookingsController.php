<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\BookingsCollection;
use App\Http\Resources\v1\BookingsResource;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class BookingsController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/bookings",
*   summary="Get all bookings",
*   description="Get all bookings",
*   tags={"Bookings"},
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
        $bookingscollection = new BookingsCollection(Bookings::where('status', '1')->get());
        
        if($bookingscollection->count() > 0) {
            return $this->sentSuccessResponse($bookingscollection, 'Get all programs successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }

/**
* @OA\Post(
*   path="/api/v1/bookings",
*   tags={"Bookings"},
*   summary="Create a new booking",
*   description="Create a new booking",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="name", type="string"),
*               @OA\Property(property="content", type="string"),
*               @OA\Property(property="phone", type="string"),
*               @OA\Property(property="date", type="string"),
*               @OA\Property(property="time", type="string"),
*               @OA\Property(property="link", type="string"),
*               @OA\Property(property="expert_id", type="integer"),
*               @OA\Property(property="user_id", type="integer"),
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
        'content' => 'required',
        'name' => 'required',
        'date' => 'required',
        'time' => 'required',
        'phone' => 'required| numeric | digits:10',
        'link' => 'required',
        'expert_id' => 'required',
        'user_id' => 'required',
    ]);
    if($validator->fails()) {
        return response()->json([
            'status_code' => 422,
            'errors' => $validator->messages()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    } else {
        $bookings = Bookings::create([
            'content' => $request->content,
            'name' => $request->name,
            'date' => $request->date,
            'time' => $request->time,
            'phone' => $request->phone,
            'link' => $request->link,
            'expert_id' => $request->expert_id,
            'user_id' => $request->user_id,
            'status' => 1,
        ]);
        $bookingsResource =  new BookingsResource($bookings);

        if($bookingsResource) {
            return $this->sentSuccessResponse($bookingsResource, 'Created successfully!!', 201);
        } else {
            return $this->sentFailureResponse(500, 'Something went wrong!!');
        }
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
