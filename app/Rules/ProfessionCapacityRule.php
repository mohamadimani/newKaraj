<?php

namespace App\Rules;

use App\Models\Profession;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProfessionCapacityRule implements ValidationRule
{
    private int $capacity;

    public function __construct(int $capacity)
    {
        $this->capacity = $capacity;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $professions = Profession::whereIn('id', $value)->get();
        
        foreach ($professions as $profession) {
            if ($profession->public_capacity > $this->capacity || $profession->private_capacity > $this->capacity) {
                $fail(__('class_rooms.capacity_is_less_than_profession_capacity', [
                    'profession_name' => $profession->title,
                    'public_capacity' => $profession->public_capacity,
                    'private_capacity' => $profession->private_capacity
                ]));
            }
        }
    }
} 