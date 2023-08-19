<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingsResource extends JsonResource
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
            'content' => $this->content,
            'date' => $this->date,
            'time' => $this->time,
            'phone' => $this->phone,
            'link' => $this->link,
            'expert' => $this->experts->name,
            'user' => $this->users->name,
            'status' => $this->status == 1 ? 'true' : 'false',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
