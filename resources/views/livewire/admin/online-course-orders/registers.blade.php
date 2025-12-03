<div class="container-fluid flex-grow-1 container-p-y">
    @php
    use Illuminate\Support\Facades\Auth;
    use App\Constants\PermissionTitle;
    @endphp
    <div class="card pb-3">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3   text-gray-800"> ثبت نام های آنلاین</h1>
            </div>
            <div class="d-flex justify-content-end mb-3">
                @include('admin.layouts.filters')
            </div>
            @include('admin.layouts.alerts')
            <div class="table-responsive">
                <style>
                    table td,
                    table th {
                        padding: 6px 4px !important;
                    }
                </style>
                <table class="table-bordered" id="dataTable" width="100%">
                    <thead>
                        <tr class="text-center font-13 ">
                            <th>#</th>
                            <th>کارآموز</th>
                            <th>موبایل</th>
                            <th>دوره</th>
                            <th> ثبت کننده</th>
                            <th>مبلغ<sub>(تومان)</sub></th>
                            <th>تخفیف <sub>(تومان)</sub></th>
                            <th>وضعیت پرداخت</th>
                            <th>کیف پول</th>
                            <th> تاریخ ثبت</th>
                            <th>گزینه ها</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orderItems as $index => $orderItem)
                        @php
                        $hasTechnical =$orderItem->hasTechnical();
                        @endphp
                        <tr class="text-center font-13">
                            <td>{{ $orderItems->firstItem() + $index }}</td>
                            @if($orderItem?->user?->student)
                            <td><a href="{{ route('students.edit',[$orderItem?->user?->student->id]) }}">{{ $orderItem->user->full_name }}</a></td>
                            @else
                            <td><a>{{ $orderItem->user->full_name }}</a></td>
                            @endif
                            <td>{{ $orderItem->user->mobile }}</td>
                            <td>{{ $orderItem?->onlineCourse?->name }}
                                @if ($hasTechnical)
                                <span class="bg-label-warning font-11">فنی حرفه ای</span>
                                @endif
                                <br>
                            </td>
                            <td>{{ $orderItem->createdBy->full_name }}</td>
                            <td>{{ number_format($orderItem->total_amount) }}</td>
                            <td>{{ number_format($orderItem->discount_amount) }}</td>
                            <td>
                                @if ($orderItem->pay_date)
                                <span class="badge badge bg-label-success">تسویه شده</span>
                                @endif
                            </td>
                            <td> <span class="badge bg-label-success">{{ $orderItem?->user ? number_format($orderItem?->user?->wallet) : 0 }}</span></td>
                            <td>{{ georgianToJalali($orderItem->created_at, true) }}</td>
                            <td>
                                @php
                                $remainingAmount = $orderItem->amount > 0 ? $orderItem->amount - $orderItem->paid_amount : $orderItem->course->price - $orderItem->paid_amount;
                                @endphp
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if ($orderItem->pay_date and $orderItem->license_key)
                                    <a class=" dropdown-item cursor-pointer license_key_{{$orderItem->id}}" onclick="copy('license_key_{{$orderItem->id}}')" data-lisence="{{ $orderItem->license_key }}">
                                        <i class="bx bxs-copy me-1 text-info"></i>کپی لایسنس
                                    </a>
                                    @endif
                                    @if (!$orderItem->pay_date)
                                    <a target="_blonk" href="{{ route('online-course-orders.show', [$orderItem->order_id]) }}" class="dropdown-item cursor-pointer add-payment-button">
                                        <i class="bx bx-dollar me-1 text-info"></i> اضافه کردن پرداخت
                                    </a>
                                    @endif
                                    <a target="_blonk" href="{{ route('payments.index', ['user_id' => $orderItem->user->id, 'back_url' => route('course-registers.index', [], false)]) }}"
                                        class="dropdown-item">
                                        <i class="tf-icons text-success fa-solid fa-money-bill"></i> {{ __('payments.page_title') }}
                                    </a>
                                    <a target="_blonk" wire:click="makeStudentAccount({{ $orderItem->user_id }})" class="dropdown-item">
                                        <i class="tf-icons text-primary fa-solid fa-edit me-2"></i>ایجاد ویرایش
                                    </a>
                                    @if (!$orderItem->pay_date)
                                    <a target="_blonk" href="{{ route('online-course-orders.show', [$orderItem->order_id]) }}" class="dropdown-item cursor-pointer add-payment-button">
                                        <i class="bx bx-edit me-1 text-info"></i> تغییر مبلغ دوره
                                    </a>
                                    @endif
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REGISTER_TECHNICAL) and !$hasTechnical)
                                    <span class="dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#technicalRegisterModal"
                                        wire:click="setTechnicalRegisterInfo({{ $orderItem->id }})">
                                        <i class="bx bx-briefcase me-1 text-gray"></i> ثبت فنی حرفه ای
                                    </span>
                                    @endif
                                    @if ($orderItem?->user->id !== Auth::user()->id and isAdminNumber())
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', [$orderItem?->user->id]) }}">
                                        <i class="bx bx-log-in me-1"></i> ورود با دسترسی کاربر
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center"><span class="text-info">سفارشی یافت نشد!</span></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <span class="d-block mt-3">{{ $orderItems->links() }}</span>
        </div>
    </div>
    @if ($orderItemId and $showModal)
    <!-- Modal for Technical Register -->
    <div class="modal d-block" id="technicalRegisterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ثبت فنی حرفه ای</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">مبلغ پرداختی</label>{{ requireSign() }}
                        <input type="number" class="form-control" wire:model.live="technicalAmount">
                    </div>
                    @if ($technicalAmount > 0)
                    <div class="mb-3">
                        <label class="form-label">روش پرداخت</label>{{ requireSign() }}
                        <select wire:model="technicalPaymentMethodId" id="payment-method" class="select2 form-select" data-allow-clear="true"
                            data-placeholder="{{ __('course_registers.select_payment_method') }}">
                            <option value="">---</option>
                            @foreach ($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاریخ پرداخت</label>{{ requireSign() }}
                        <input data-jdp wire:model="technicalPayDate" type="text" id="pay-date" class="form-control " placeholder="تاریخ پرداخت" value="{{ old('pay_date') }}">
                    </div>
                    <div class="col-sm-6 mb-1">
                        <label class="form-label" for="technicalPaidImage">تصویر فیش پرداخت </label> {{ requireSign() }}
                        <input wire:model="technicalPaidImage" type="file" id="technicalPaidImage" class="form-control" required>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">توضیحات</label>{{ requireSign() }}
                        <textarea class="form-control" wire:model="technicalAmountDescription"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="$set('showModal',null)">لغو</button>
                    <button type="button" class="btn btn-primary" wire:click="registerTechnical({{ $orderItemId }})" data-bs-dismiss="modal">ثبت</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    <script>
        function copy(item) {
            var lisence = $('.' + item).attr('data-lisence');
            navigator.clipboard.writeText(lisence);
            $('span.copy_' + item).removeClass('d-none');
            $('span.copy_' + item).fadeIn(100);
            setTimeout(() => {
                $('span.copy_' + item).fadeOut(300);
            }, 1500);
        }
    </script>
</div>
