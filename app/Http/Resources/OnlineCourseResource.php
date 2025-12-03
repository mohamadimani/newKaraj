<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OnlineCourseResource extends JsonResource
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
            'name' => $this->name,
            'amount' => $this->amount,
            'duration_hour' => $this->duration_hour,
            'is_active' => $this->is_active,
            'registered_count' => $this->registered_count,
            'spot_key' => $this->spot_key,
            'discount_amount' => $this->discount_amount,
            'discount_start_at' => $this->discount_start_at,
            'discount_expire_at' => $this->discount_expire_at,
            'discount_start_at_jalali' => $this->discount_start_at_jalali,
            'discount_expire_at_jalali' => $this->discount_expire_at_jalali,
            // 'link' => 'https://newDeniz.com?online_course_id=' . $this->id . '&branch_id=1',
            'link' => 'https://my.newDeniz.com?online_course_id=' . $this->id . '&branch_id=1',
        ];
    }
}
