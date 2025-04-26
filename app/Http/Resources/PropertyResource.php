<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HouseResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'price' => $this->price,
            'type' => $this->type,
            // Use HouseResource directly for a hasOne relationship
            'house_details' => $this->type === 'house' ? new HouseResource($this->whenLoaded('house')) : null,
        ];
    }
}
