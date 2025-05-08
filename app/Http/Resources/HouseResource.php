<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
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
            'bedrooms' => $this->bedroom,
            'bathrooms' => $this->bathroom,
            'parking' => $this->parking,
            'pool' => $this->pool,
            'area' => $this->area,
            //'year_built' => $this->year_built,
            'property_id' => $this->property_id,
            'house_type' => $this->house_type,
        ];
    }
}
