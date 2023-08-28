<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ExpertRegisterResource;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\v1\UsersRegisterResource;
use App\Models\Users;
use OpenApi\Annotations as OA;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth/v1:api', ['except' => ['login', 'userRegister', 'profile', 'logout', 'expertRegister']]);
    }

/**
* @OA\Post(
*   path="/api/auth/v1/user-register",
*   tags={"Authentication"},
*   summary="Create a new user",
*   description="Create a new user",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="name", type="string"),
*               @OA\Property(property="username", type="string"),
*               @OA\Property(property="phone", type="string"),
*               @OA\Property(property="profession_id", type="string"),
*               @OA\Property(property="email", type="string"),
*               @OA\Property(property="city_id", type="string"),
*               @OA\Property(property="password", type="string"),
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
    public function userRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required | string',
            'phone' => 'required | numeric | digits:10',
            'profession_id' =>  'required',
            'city_id' =>  'required',
            'email' =>  'required | email | unique:users',
            'password' =>  'required | min:8',
        ]);
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'phone' => $request->phone,
                'email' => $request->email,
                'profession_id' => $request->profession_id,
                'city_id' => $request->city_id,
                'password' => Hash::make($request->password),
                'type' => 1,
                'status' => 1
            ]);
            $userResource =  new UsersRegisterResource($user);
            if($userResource) {
                return $this->sentSuccessResponse($userResource, 'User register successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }


/**
* @OA\Post(
*   path="/api/auth/v1/expert-register",
*   tags={"Authentication"},
*   summary="Create a new expert",
*   description="Create a new expert",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={
*               @OA\Property(property="name", type="string"),
*               @OA\Property(property="username", type="string"),
*               @OA\Property(property="phone", type="string"),
*               @OA\Property(property="profession_id", type="string"),
*               @OA\Property(property="company", type="string"),
*               @OA\Property(property="experience", type="string"),
*               @OA\Property(property="email", type="string"),
*               @OA\Property(property="password", type="string"),
*               @OA\Property(property="project", type="string"),
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
    public function expertRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required| string',
            'phone' => 'required| numeric | digits:10',
            'profession_id' =>  'required',
            'company' =>  'required',
            'experience' =>  'required',
            'email' =>  'required | email | unique:users',
            'password' =>  'required | min:8',
            'project' =>  'required',
        ]);
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $expert = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'phone' => $request->phone,
                'profession_id' => $request->profession_id,
                'company' => $request->company,
                'experience' => $request->experience,
                'email' => $request->email,
                'project' => $request->project,
                'password' => Hash::make($request->password),
                'type' => 2,
                'status' => 1
            ]);
            $expertResource =  new ExpertRegisterResource($expert);
            if($expertResource) {
                return $this->sentSuccessResponse($expertResource, 'Expert register successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }


/**
* @OA\Post(
*   path="/api/auth/v1/login",
*   tags={"Authentication"},
*   summary="Check login user/expert",
*   description="Check login user/expert",
* 
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={               
*               @OA\Property(property="username", type="string"),           
*               @OA\Property(property="password", type="string"),
*               },
*           ),
*       ),
*   ),
*
*   @OA\Response(
*       response = 201,
*       description = "Check Successfully!!",
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
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required| string',
            'password' =>  'required | min:8',
        ]);
        if($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'errors' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } 
        if(!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status_code' => 401,
                'errors' => 'Unauthorized!!'
            ], 401);
        }
        return $this->createNewToken($token);
    }

    public function createNewToken($token) {
        $user = [
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'type' => auth()->user()->type,
            'status'=> auth()->user()->status == 1 ? 'true' : 'false',
            'created_at'=> auth()->user()->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> auth()->user()->updated_at->format('Y-m-d H:i:s'),
        ];

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
            'status_code' => 201
        ]);
    }


/**
* @OA\Get(
*   path="/api/auth/v1/profile",
*   tags={"Authentication"},
*   summary="Get user/expert",
*   description="Get user/expert",
*   security={{ "bearerAuth":{} }},
*   
*   @OA\Response(
*       response = 201,
*       description = "Check Successfully!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 500,
*       description = "Something went wrong!!",
*       @OA\JsonContent()
*    ),
*)
*/
    public function profile() {
        $user = [
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'type' => auth()->user()->type,
            'status'=> auth()->user()->status == 1 ? 'true' : 'false',
            'created_at'=> auth()->user()->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> auth()->user()->updated_at->format('Y-m-d H:i:s'),
        ];
        
        return response()->json([
            'status_code' => 200,
            'user' => $user
        ], 200);
    }

/**
* @OA\Post(
*   path="/api/auth/v1/logout",
*   tags={"Authentication"},
*   summary="Logged out",
*   description="Logged out",
*
*   @OA\RequestBody(      
*       required=true,
*       @OA\JsonContent(
*           @OA\Schema(
*               properties={                 
*               @OA\Property(property="token", type="string"),
*               },
*           ),
*       ),
*   ),
*
*   @OA\Response(
*       response = 201,
*       description = "Logged out Successfully!!",
*       @OA\JsonContent()
*    ),
*   @OA\Response(
*       response = 500,
*       description = "Something went wrong!!",
*       @OA\JsonContent()
*    ),
*)
*/
    public function logout() {
        auth()->logout();
        
        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}
