<?php

namespace App\Http\Resources\Game;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'game_date' => $this->game_date,
            'total_score' => $this->total_score,
            'status' => $this->status,
            'location' => [
                'id'    => $this->location->id,
                'name'  => $this->location->name,
            ],
            'ball' => [
                'id'    => $this->ball->id,
                'name'  => $this->ball->name,
                'weight' => $this->ball->weight,
                'color' => $this->ball->color,
            ],
            'frames' => $this->frames
        ];
    }
}
