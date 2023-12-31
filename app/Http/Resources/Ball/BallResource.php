<?php

namespace App\Http\Resources\Ball;

use Illuminate\Http\Resources\Json\JsonResource;

class BallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'weight' => $this->weight,
            'color' => $this->color,
            'type' => $this->type,
        ];
    }
}
