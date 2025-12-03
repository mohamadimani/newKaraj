<?php

namespace App\Livewire\Users\Reference;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $referenceCode;

    public function render()
    {
        $referenceOrders = [];
        if (user()->reference_code) {
            $referenceOrders =  Order::where('reference_code', user()->reference_code)->where('pay_date', '>', 0)->orderBy('id', 'DESC')->get();
        }
        return view('livewire.users.reference.index', compact('referenceOrders'));
    }

    public function setReferenceCode()
    {
        $this->referenceCode = $this->generateReferenceCode();
        $user = User::find(user()->id);
        $user->reference_code = $this->referenceCode;
        return $user->save();
    }

    public function generateReferenceCode()
    {
        $code =  rand(100000, 999999);
        if (User::where('reference_code', $code)->first()) {
            $code =  $this->generateReferenceCode();
        }
        return $code;
    }
}
