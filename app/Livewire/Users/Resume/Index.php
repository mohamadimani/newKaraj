<?php

namespace App\Livewire\Users\Resume;

use App\Models\Profession;
use App\Models\Province;
use App\Models\User;
use App\Models\UserResumePerofession;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $mobile;
    public $military_status;
    public $province;
    public $birth_date;
    public $address;
    public $gender;
    public $marital_status;
    public $personal_image;
    public $professionId;
    public $persent;
    use WithPagination, LivewireAlert, WithFileUploads;
    public function render()
    {
        $userResumePerofessions = UserResumePerofession::where([
            'user_id' => user()->id,
        ])->orderBy('id', 'DESC')->get();

        $provinces = Province::all();
        $professions = Profession::active()->get();
        return view('livewire.users.resume.index', compact('provinces', 'professions', 'userResumePerofessions'));
    }

    public function updateResume()
    {
        $user = User::find(user()->id);
        $user->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            // 'mobile' => $this->mobile,
            'gender' => $this->gender,
            'province_id' => $this->province,
            'birth_date' =>  $this->birth_date ? toGeorgianDate($this->birth_date) : null,
            'address' => $this->address,
        ]);

        $user->student->military_status = $this->military_status;
        $user->student->marital_status = $this->marital_status;
        if ($user and $user->student->save()) {
            return $this->alert('success', __('public.messages.successfully_saved'));
        }
        return $this->alert('error', __('public.messages.error_in_saving'));
    }

    public function setProvince($provinceId)
    {
        $this->province = $provinceId;
    }

    public function setProfession($professionId)
    {
        $this->professionId = $professionId;
    }

    public function removePerofession($userResumeProfessionId)
    {
        if ($userResumePerofession = UserResumePerofession::find($userResumeProfessionId)) {
            $userResumePerofession->delete();
            return $this->alert('success', __('public.messages.successfully_deleted'));
        } else {
            return $this->alert('error', __('public.messages.error_in_deleting'));
        }
    }

    public function storeProfession()
    {
        $this->validate([
            'professionId' => 'required',
            'persent' => 'required',
        ]);
        $userResumePerofession = UserResumePerofession::where([
            'user_id' => user()->id,
            'profession_id' => $this->professionId,
        ])->first();

        if ($userResumePerofession) {
            $userResumePerofession->persent = $this->persent;
            $userResumePerofession->save();
            return $this->alert('success', __('public.messages.successfully_saved'));
        } else {
            $res = UserResumePerofession::create([
                'user_id' => user()->id,
                'profession_id' => $this->professionId,
                'persent' => $this->persent,
            ]);
            if ($res) {
                return $this->alert('success', __('public.messages.successfully_saved'));
            } else {
                return $this->alert('error', __('public.messages.error_in_saving'));
            }
        }
    }

    public function mount()
    {
        $this->first_name = user()->first_name;
        $this->last_name = user()->last_name;
        $this->email = user()->email;
        $this->mobile = user()->mobile;
        $this->gender = user()->gender;
        $this->province = user()->province_id;
        $this->birth_date = user()->birth_date;
        $this->address = user()->address;
        $this->military_status = user()->student->military_status;
        $this->marital_status = user()->student->marital_status;
        $this->personal_image = user()->student->personal_image;
    }
}
