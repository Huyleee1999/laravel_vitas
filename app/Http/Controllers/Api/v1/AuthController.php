<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
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
        $this->middleware('auth/v1:api', ['except' => ['login', 'register', 'profile', 'logout']]);
    }

/**
* @OA\Post(
*   path="/api/auth/v1/register",
*   tags={"Authentication"},
*   summary="Create a new user/expert",
*   description="Create a new user/expert",
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
*               @OA\Property(property="type", type="string"),
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
    public function register(Request $request) {
        if($request->type == 1) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'username' => 'required| string',
                'phone' => 'required| numeric | digits:10',
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
                    return $this->sentSuccessResponse($userResource, 'Register successfully!!', 200);
                } else {
                    return $this->sentFailureResponse(500, 'Something went wrong!!');
                }
            }
        } 
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required| string',
            'phone' => 'required| numeric | digits:10',
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
                return $this->sentSuccessResponse($userResource, 'Register successfully!!', 200);
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
*   summary="Logged out user/expert",
*   description="Logged out user/expert",
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
