<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersRegisterCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        // return [
        //     'name' => $this->name,
        //     'username' => $this->username,
        //     'phone' =>$this->phone,
        //     'profession_id'=> $this->profession_id,
        //     'city_id'=> $this->city_id,
        //     'email' => $this->email,
        //     'password' => $this->password,
        //     'type' => $this->type,
        //     'status'=> $this->status == 1 ? 'true' : 'false',
        //     'created_at'=> $this->created_at,
        //     'updated_at'=> $this->updated_at,
        // ];
    }
}
