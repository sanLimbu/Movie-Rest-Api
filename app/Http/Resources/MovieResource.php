<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->user()->pluck('name')->first(),
            'actors' => $this->actors()->pluck('name'),
            'name' => $this->name,
            'release_date' => $this->release_date,
            'genres' => $this->genres()->pluck('name'),
            
        ];
    }
}
