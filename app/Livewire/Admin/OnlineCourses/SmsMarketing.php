<?php

namespace App\Livewire\Admin\OnlineCourses;

use App\Models\OnlineCourse;
use App\Models\OnlineMarketingSms;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SmsMarketing extends Component
{
    public $online_course_id = [];
    public $target_type = 'clue';
    public $after_time;
    public $message;
    public $editRowId;
    public $edit_target_type;
    public $edit_after_time;
    public $edit_message;
    public $edit_online_course_id;
    public $smsDeleteId;
    protected $listeners = ['delete'];
    public $targetType = [
        'clue' => 'سرنخ',
        'student' => 'کارآموز',
    ];

    use LivewireAlert;
    public function render()
    {
        $onlineCourses = OnlineCourse::Active()->get();
        $onlineMarketingSms = OnlineMarketingSms::orderBy('id', 'DESC')->get();
        return view('livewire.admin.online-courses.sms-marketing', compact('onlineCourses', 'onlineMarketingSms'));
    }

    public function setOnlineCourseId($courseId)
    {
        $this->online_course_id = $courseId;
    }

    public function edit($smsId)
    {
        $onlineSms = OnlineMarketingSms::find($smsId);
        $this->editRowId = $smsId;
        $this->edit_target_type = $onlineSms->target_type;
        $this->edit_after_time = $onlineSms->after_time / 86400;
        $this->edit_message = $onlineSms->message;
        $this->edit_online_course_id = $onlineSms->online_course_id;
    }

    public function store()
    {
        $this->validate([
            'online_course_id' => 'required',
            'target_type' => 'required',
            'after_time' => 'required',
            'message' => 'required',
        ]);

        foreach ($this->online_course_id as $id) {
            $onlineMarketingSms = OnlineMarketingSms::create([
                'online_course_id' => $id,
                'target_type' => $this->target_type,
                'after_time' => $this->after_time * 86400,
                'message' => $this->message,
                'created_by' => user()->id,
            ]);
        }
        $this->reset();
        $this->online_course_id = [];
        return $this->alert('success', __('public.messages.successfully_saved'));
    }

    public function update($smsId)
    {
        $this->validate([
            'edit_online_course_id' => 'required',
            'edit_target_type' => 'required',
            'edit_after_time' => 'required',
            'edit_message' => 'required',
        ]);

        $onlineSms = OnlineMarketingSms::find($smsId)->update([
            'online_course_id' => $this->edit_online_course_id,
            'target_type' => $this->edit_target_type,
            'after_time' => $this->edit_after_time * 86400,
            'message' => $this->edit_message,
            'created_by' => user()->id,
        ]);

        $this->reset();
        if ($onlineSms) {
            return $this->alert('success', __('public.messages.successfully_updated'));
        }
        return $this->alert('error', __('public.messages.error_in_updating'));
    }

    public function updateStatus($smsId, $status)
    {
        $onlineSms = OnlineMarketingSms::find($smsId);
        $onlineSms->is_active = $status;
        if ($onlineSms->save()) {
            return $this->alert('success', __('public.messages.successfully_updated'));
        }
        return $this->alert('error', __('public.messages.error_in_updating'));
    }

    public function deleteConfirm($smsId)
    {
        $this->confirm('آیا مطمئن هستید؟', [
            'onConfirmed' => 'delete',
        ]);
        $this->smsDeleteId = $smsId;
    }

    public function delete()
    {
        $onlineSms = OnlineMarketingSms::find($this->smsDeleteId);
        $onlineSms->deleted_by = user()->id;
        $onlineSms->save();
        $onlineSms->delete();

        return $this->alert('success', __('group_descriptions.messages.successfully_deleted'));
    }
}
