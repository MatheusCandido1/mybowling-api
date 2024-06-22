<?php

namespace App\Http\Resources\Ranking;

use Illuminate\Http\Resources\Json\JsonResource;

class RankingResource extends JsonResource
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
            'rank' => $this->rank,
            'id' => $this->id,
            'total_score' => $this->total_score,
            'game_date' => $this->game_date,
            'name' => $this->user->name,
            'avatar' => $this->user->avatar,
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
            'frames' => $this->frames,
        ];
    }
}
