<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionResource extends JsonResource
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
            'title' => $this->title,
            // 'link' => 'https://newDeniz.com/api/v1/professions/' . $this->id . '/courses',
            'link' => 'https://my.newDeniz.com/api/v1/professions/' . $this->id . '/courses',
        ];
    }
}
