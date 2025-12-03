<?php

namespace App\Livewire\Users\Wallet;

use App\Models\TransferWalletLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

use function Laravel\Prompts\alert;

class Index extends Component
{
    public $mobile;
    public $user;
    public $amount;
    public $isStudent;

    use LivewireAlert;
    public function render()
    {
        $transfers = TransferWalletLog::where('from_user_id', user()->id)->orderBy('id','DESC')->get();
        return view('livewire.users.wallet.index', compact('transfers'));
    }

    public function search()
    {
        if (!$this->mobile or !$this->amount) {
            return  $this->alert('error', 'ورود موبایل و مبلغ الزامی میباشد');
        }
        if (user()->wallet < $this->amount) {
            return  $this->alert('error', 'مبلغ وارد شده بیشتر از موجوری کیف پول شماست');
        }
        if ($this->amount < 50000) {
            return  $this->alert('error', 'حداقل مبلغ قابل انتقال 50,000 تومان میباشد');
        }
        if (!$this->user = User::where('mobile', $this->mobile)->first()) {
            return  $this->alert('error', 'کاربری یافت نشد');
        }
        if ($this->user->student) {
            $this->reset();
            $this->isStudent = true;
            return  $this->alert('error', 'کاربری قبلا در دوره شرکت کرده است');
        }
    }

    public function cancel()
    {
        $this->reset();
    }

    public function transfer()
    {
        DB::beginTransaction();
        if ($this->mobile and $this->amount) {

            addWallet($this->user, $this->amount);
            withdrawWallet(user(), $this->amount);

            $description = 'انتقال اعتبار از کیف پول به کیف پول';
            TransferWalletLog::setLog(user()->id, $this->user->id, $this->amount, $description);
            DB::commit();
            $this->reset();
            return  $this->alert('success', 'انتقال اعتبار با موفقیت انجام شد');
        }
        DB::rollBack();
        return  $this->alert('error', 'مشکلی در انتقال اعتبار پیش آمد!');
    }
}
