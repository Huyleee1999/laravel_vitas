<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluatesResource extends JsonResource
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
            'rate' => $this->rate,
            // 'profession_id'=> new ProfessionsResource($this->professions),
            'expert'=> $this->experts->name,
            'user'=> $this->users->name,
            'content' => $this->content,
            'status'=> $this->status == 1 ? 'true' : 'false',
            'created_at'=> $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> $this->updated_at->format('Y-m-d H:i:s'),
            
        ];
    }
}
