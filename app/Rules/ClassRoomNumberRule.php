<?php

namespace App\Rules;

use App\Models\ClassRoom;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ClassRoomNumberRule implements ValidationRule
{
    public function __construct(
        protected string $number,
        protected int $branchId,
        protected ?int $classRoomId = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $classRoom = ClassRoom::query()
            ->where('number', $this->number)
            ->where('branch_id', $this->branchId)
            ->when($this->classRoomId, function ($query) {
                $query->where('id', '!=', $this->classRoomId);
            })
            ->first();

        if ($classRoom) {
            $fail(__('class_rooms.number_already_exists'));
        }
    }
}
