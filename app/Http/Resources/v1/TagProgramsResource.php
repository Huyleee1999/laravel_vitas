<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagProgramsResource extends JsonResource
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
            'tag' => $this->tags->name,
            'program' => $this->programs->name,
            'created_at'=> $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
