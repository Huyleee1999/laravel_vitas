<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;
use App\Http\Resources\v1\ExpertRegisterCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\v1\ExpertRegisterResource;


class ExpertRegisterController extends Controller
{
    public function index() {
        $expertiRegisterCollection = new ExpertRegisterCollection(Expert::where('status', '1')->where('type', '2')->get());
        
        if($expertiRegisterCollection->count() > 0) {
            return $this->sentSuccessResponse($expertiRegisterCollection, 'Get expert register successfully!!', 200);
        } else {
            return $this->sentFailureResponse4(404, 'Data not found!!');
        }
    }

    public function store(Request $request) {
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
                'status' => 422,
                'errors' => $validator->messages() 
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $expertRegister = Expert::create([
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
            $expertRegisterResource =  new ExpertRegisterResource($expertRegister);

            if($expertRegisterResource) {
                return $this->sentSuccessResponse($expertRegisterResource, 'Created successfully!!', 201);
            } else {
                return $this->sentFailureResponse(500, 'Something went wrong!!');
            }
        }
    }
}
