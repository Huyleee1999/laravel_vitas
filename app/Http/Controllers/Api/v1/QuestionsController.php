<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\QuestionsCollection;
use App\Http\Resources\v1\QuestionsResource;
use App\Models\Questions;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;


class QuestionsController extends Controller
{

/**
* @OA\Get(
*   path="/api/v1/questions",
*   summary="Get all Questions",
*   description="Get all Questions",
*   tags={"Questions"},
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
        $questionsCollection = new QuestionsCollection(Questions::where('status', '1')->get());
        
        if($questionsCollection->count() > 0) {
            return $this->sentSuccessResponse($questionsCollection, 'Get all questions successfully!!', 200);
        } else {
            return $this->sentFailureResponse4(404, 'Data not found!!');
        }
    }


    /**
* @OA\Post(
*   path="/api/v1/questions",
*   tags={"Questions"},
*   summary="Create a new question",
*   description="Create a new question",
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
            'content' => 'required',
            'profession_id' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $questions = Questions::create([
                'content' => $request->content,
                'profession_id' => $request->profession_id,
                'status' => 1,
            ]);
            $questionsResource =  new QuestionsResource($questions);

            if($questionsResource) {
                return $this->sentSuccessResponse($questionsResource, 'Created successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }
    
    /**
* @OA\Get(
*   path="/api/v1/questions/{id}",
*   summary="Get question by id",
*   description="Get question by id",
*   tags={"Questions"},
*   @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="Id of the question",
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
    $questions = Questions::find($id);
    if($questions) {
        $questionsResource = new QuestionsResource($questions);
        return $this->sentSuccessResponse($questionsResource, 'Get question by id successfully!!', 200);
    } else {
        return $this->sentFailureResponse(404, 'Data not found!!');
    }
}

   
    public function update(Request $request, string $id)
    {
        //
    }


public function destroy($id)
{
    $questions = Questions::find($id);

    if($questions) {
        $questions->delete();
        return response()->json([
            'status_code' => '200',
            'message' => 'Delete successfully!!'
        ], Response::HTTP_OK);
    } else {
       return $this->sentFailureResponse(404, 'Data not found!!');
    }
}
}
