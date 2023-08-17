<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $user = Users::where('username', $request->username)->where('status', '1')->where('type', '1')->first();

        if(!$user || !Hash::check($request->password, $user->password, [])) {
            return $this->sentFailureResponse(404, 'User not exist!!');
        }

        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if ($data) {
            return $this->sentSuccessResponse($data, 'Login successfully!!', 200);
        } else {
            return $this->sentFailureResponse(500, 'Something went wrong!!');
        }
    }

    
}
