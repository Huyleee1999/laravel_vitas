<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpertRegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'phone' =>$this->phone,
            'profession_id'=> $this->professions->name,
            'company'=> $this->company,
            'experience'=> $this->experience,
            'email' => $this->email,
            'password' => $this->password,
            'project' => $this->project,
            'type' => $this->type,
            'status'=> $this->status == 1 ? 'true' : 'false',
            'created_at'=> $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
