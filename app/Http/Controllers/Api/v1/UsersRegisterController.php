<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Resources\v1\UsersRegisterCollection;
use App\Http\Resources\v1\UsersRegisterResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class UsersRegisterController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function index()
    {
        $userscollection =  new UsersRegisterCollection(Users::where('status', '1')->where('type', '1')->get());
        
        if ($userscollection->count() > 0) {
            return $this->sentSuccessResponse($userscollection, 'Get all users successfully!!', 200);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }

    public function store(Request $request)
    {
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
            $users = Users::create([
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
            $usersResource =  new UsersRegisterResource($users);

            if($usersResource) {
                return $this->sentSuccessResponse($usersResource, 'Created successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }

    public function show($id)
    {
        $users = Users::where('status', '1')->where('type', '1')->find($id);
        if($users) {
            $usersResource = new UsersRegisterResource($users);
            return $this->sentSuccessResponse($usersResource, 'Get user by id successfully!!', 201);
        } else {
            return $this->sentFailureResponse(404, 'Data not found!!');
        }
    }
}
