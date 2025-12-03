<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IsfahanCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->capacity - $this->courseRegisters->count() > 0) {
            return [
                'id' => $this->id,
                'title' => $this->title,
                'capacity' => $this->capacity,
                // 'register_count' => $this->courseRegisters->count(),
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'price' => $this->price,
                'week_days' => $this->week_days,
                'duration_hours' => $this->duration_hours,
                'branch' => $this->branch->name,
                'is_active' => $this->is_active,
                'course_type' => $this->course_type,
                // 'link' => 'https://newDeniz.com?course_id=' . $this->id . '&branch_id=10',
                'link' => 'https://my.newDeniz.com?course_id=' . $this->id . '&branch_id=10',
            ];
        } else {
            return [];
        }
    }
}
