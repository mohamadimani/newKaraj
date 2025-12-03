<?php

namespace App\Livewire\Admin\Technicals;

use App\Enums\Technical\StatusEnum;
use App\Models\Secretary;
use App\Models\Technical;
use App\Models\TechnicalAddress;
use App\Models\TechnicalDescription;
use App\Models\TechnicalExam;
use App\Repositories\Course\TechnicalRepository;
use App\Repositories\User\SecretaryRepository;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Introduced extends Component
{
    public $technical_description;
    public $search;
    public $startDate;
    public $endDate;
    public $practical_date;
    public $practical_address;
    public $practical_description;
    public $selectedSecretaryId;
    public $written_date;
    public $written_description;
    public $technicalId;
    public $technicalModel;
    use WithPagination, LivewireAlert;
    public $paginationTheme = 'bootstrap';
    protected $listeners = ['updateStatus'];
    public function render()
    {
        $technicals = resolve(TechnicalRepository::class)->getListQuery()->where('status', StatusEnum::INTRODUCED); // this code has to be duplicate to work

        if (mb_strlen($this->search) >= 2) {
            $search = trim($this->search);
            $technicals = $technicals->whereHas('user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });

            $technicals = $technicals->orWhereHas('course', function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%");
            });

            $technicals = $technicals->orWhereHas('user.student', function ($query) use ($search) {
                $query->where('national_code', 'LIKE', "%$search%");
            });
        }
        if ($this->selectedSecretaryId) {
            $secretary = Secretary::find($this->selectedSecretaryId);
            $technicals = $technicals->where('created_by', $secretary->user_id);
        }
        if ($this->startDate) {
            $technicals->where('updated_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $technicals->where('updated_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        $technicals = $technicals->where('status', StatusEnum::INTRODUCED) // this code has to be duplicate to work
            ->with('user.student')
            ->with('technicalDescriptions')
            ->orderBy('id', 'desc')
            ->paginate(30);

        $technicalAddress = TechnicalAddress::active()->get();
        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->orderBy('is_active', 'desc')->get();
        return view('livewire.admin.technicals.introduced', compact('technicals', 'technicalAddress', 'secretaries'));
    }

    public function updateStatusToProcessing($technicalId)
    {
        $this->confirm('به در حال اقدام تغییر کند؟', [
            'onConfirmed' => 'updateStatus',
        ]);
        $this->technicalId = $technicalId;
    }

    public function updateStatus()
    {
        $technical = Technical::find($this->technicalId);
        $technical->status = StatusEnum::PROCESSING->value;
        $technical->save();
        return $this->alert('success', __('public.messages.successfully_done'));
    }

    public function storeWrittenExam()
    {
        $this->validate([
            'written_date' => 'required',
            'written_description' => 'required|min:3',
            'technicalId' => 'required|exists:technicals,id',
        ]);
        $technicalExamExist = TechnicalExam::where('technical_id', $this->technicalId)->where('exam_date', '>', time())->where('exam_type', 'written')->first();
        if ($technicalExamExist) {
            return $this->alert('error', 'آزمون کتبی قبلا ثبت شده است');
        }
        $writtenDate = Verta::parse($this->written_date)->timestamp;
        // if ($writtenDate <= time()) {
        //     return $this->alert('error', 'تاریخ آزمون کتبی نمیتواند کمتر از تاریخ حال باشد');
        // }

        $technicalExamExist = TechnicalExam::where('technical_id', $this->technicalId)->where('exam_date', '>', time())->where('exam_type', 'practical')->first();
        if ($technicalExamExist) {
            return $this->alert('error', 'باید آزمون عملی انجام شده باشد');
        }

        $technicalExam = TechnicalExam::create([
            'technical_id' => $this->technicalId,
            'exam_date' => $writtenDate,
            'exam_description' => $this->written_description,
            'exam_type' => 'written',
        ]);
        if ($technicalExam) {

            $user = $technicalExam->technical->user;
            $name = $technicalExam->technical->user->full_name;
            $text =  "$name عزیز \n" .
                " آزمون کتبی " . $technicalExam->technical->course->profession->title . " شما در تاریخ " . $this->written_date .
                " برگزار میگردد لطفا ۳ روز قبل از آزمون کارت ورود به جلسه خود را از کافی نت و از سایت" . "\n" .
                "https://azmoon.portaltvto.com/card/card/index/1/80" . "\n" . "دریافت کنید" . "\n" .
                "(آدرس و ساعت دقیق آزمون داخل کارت ورود به جلسه قید شده است)" . "\n" .
                "مدارک مورد نیاز : شناسنامه و کارت ملی و کپی کارت ورود به جلسه" . "\n" .
                "در صورت ارور موقع وارد شدن به سایت برای دریافت کارت لطفا شماره شناسنامه خود را 0 زده و موارد اعداد را به انگلیسی وارد کنید" . "\n" .
                "نتیجه آزمون کتبی و عملی:" . "\n" .
                "http://azmoon.portaltvto.com/result/result/index/1/80" . "\n" .
                "آموزشگاه دنیز";

            sendMessage($user, $text, 'kavehnegar');

            return $this->alert('success', __('public.messages.successfully_done'));
        }
        return $this->alert('error', 'مشکلی در ثبت آزمون کتبی رخ داده است');
    }

    public function storePracticalExam()
    {
        $this->validate([
            'practical_date' => 'required',
            'practical_description' => 'required|min:3',
            'technicalId' => 'required|exists:technicals,id',
            'practical_address' => 'required|exists:technical_addresses,id',
        ]);

        $practicalDate = Verta::parse($this->practical_date)->timestamp;
        if ($practicalDate <= time()) {
            return $this->alert('error', 'تاریخ آزمون عملی نمیتواند کمتر از تاریخ حال باشد');
        }

        $writtenDate = TechnicalExam::where('technical_id', $this->technicalId)->where('exam_date', '>', time())->where('exam_type', 'written')->first();
        if ($writtenDate and $writtenDate = Verta::parse($writtenDate->exam_date)->timestamp and $writtenDate > time()) {
            return $this->alert('error', 'باید آزمون کتبی انجام شده باشد');
        }

        $technicalExamExist = TechnicalExam::where('technical_id', $this->technicalId)->where('exam_date', '>', time())->where('exam_type', 'practical')->first();
        if ($technicalExamExist) {
            return $this->alert('error', 'آزمون عملی قبلا ثبت شده است');
        }

        $technicalExam = TechnicalExam::create([
            'technical_id' => $this->technicalId,
            'exam_date' => $practicalDate,
            'exam_description' => $this->practical_description,
            'exam_type' => 'practical',
            'technical_address_id' => $this->practical_address,
        ]);
        if ($technicalExam) {

            $user = $technicalExam->technical->user;
            $name = $technicalExam->technical->user->full_name;
            $technicalAddress = TechnicalAddress::find($this->practical_address);
            $technicalAddressTitle = $technicalAddress->title;
            $technicalAddressAddress = $technicalAddress->address;

            $text =  "$name عزیز" . "\n" .
                " تاریخ آزمون عملی "
                . $technicalExam->technical->course->profession->title . "\n"
                . $this->practical_date . " در "
                . $technicalAddressTitle .
                " برگزار میگردد" . "\n" .
                "آدرس: " . $technicalAddressAddress . "\n" .
                $this->practical_description  . "\n" .
                " آزمون عملی کارت ورود به جلسه ندارد" . "\n" .
                "حتما مدارک شناسایی به همراه داشته باشید
                نتیجه آزمون کتبی و عملی:" . "\n" .
                "http://azmoon.portaltvto.com/result/result/index/1/80" . "\n" .
                "آموزشگاه دنیز";

            sendMessage($user, $text, 'kavehnegar');

            return $this->alert('success', __('public.messages.successfully_done'));
        }
        return $this->alert('error', 'مشکلی در ثبت آزمون عملی رخ داده است');
    }

    public function storeTechnicalDescription()
    {
        $this->validate([
            'technical_description' => 'required|min:3',
            'technicalId' => 'required|exists:technicals,id',
        ]);

        $technicalDescription = TechnicalDescription::create([
            'technical_id' => $this->technicalId,
            'description' => $this->technical_description,
            'created_by' => user()->id,
        ]);
        if ($technicalDescription) {
            $this->technical_description = '';
            return $this->alert('success', __('public.messages.successfully_done'));
        }
        return $this->alert('error', 'مشکلی در ثبت توضیحات رخ داده است');
    }

    public function updatedTechnicalId()
    {
        $this->technicalModel = technical::find($this->technicalId);
    }
}
