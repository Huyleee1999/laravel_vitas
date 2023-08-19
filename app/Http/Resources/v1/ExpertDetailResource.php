<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpertDetailResource extends JsonResource
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
            'description' => $this->description,
            'rate' => $this->rate,
            'price' => $this->price,
            'customer' => $this->customer,
            'position' => $this->position,
            'experience' => $this->experience,
            'type' => $this->type,
            'status'=> $this->status == 1 ? 'true' : 'false',
            'created_at'=> $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
