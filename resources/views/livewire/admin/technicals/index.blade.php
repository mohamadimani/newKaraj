<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">درحال اقدام ها</span>
            <a href="{{ route('technicals.introduced') }}" class="btn btn-primary"> <i class="bx bx-arrow-back me-1"></i> معرفی شده ها</a>
        </div>
        <div class="card-body">
            <div class="d-flex gap-2 col-md-12">
                <div class="col-md-3">
                    <label for="search">{{ __('public.search') }}</label> :
                    <input type="text" class="form-control " wire:model.live.debounce.500ms="search" placeholder="جستجو ...">
                </div>
                <div class="col-md-2" wire:ignore>
                    <label for="selectedSecretaryId">{{ __('clues.secretary') }}</label> :
                    <select class="form-control select2 " id="selectedSecretaryId" wire:model.live.debounce.500ms="selectedSecretaryId" onchange="myFunction()">
                        <option value="0">{{ __('public.all') }}</option>
                        @foreach($secretaries as $secretary)
                        <option value="{{ $secretary->id }}">{{ $secretary->user->fullName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="startDate">از</label> :
                    <input data-jdp type="text" class="form-control " wire:model.live.debounce.500ms="startDate" placeholder="تاریخ">
                    @include('admin.layouts.jdp', ['time' => false])
                </div>
                <div class="col-md-2">
                    <label for="endDate">تا</label> :
                    <input data-jdp type="text" class="form-control " wire:model.live.debounce.500ms="endDate" placeholder="تاریخ">
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('users.mobile') }}</th>
                        <th>{{ __('users.national_code') }}</th>
                        <th>تاریخ تولد</th>
                        <th>نام پدر</th>
                        <th>دوره</th>
                        <th>شعبه</th>
                        <th>مشاور</th>
                        <th>{{ __('users.created_at') }}</th>
                        <th>پرداختی</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($technicals as $technical)
                    <tr class="text-center">
                        <td>{{ calcIterationNumber($technicals, $loop) }}</td>
                        <td><a target="_blank" href="{{ route('students.edit', $technical->student_id) }}">{{ $technical->user->fullName }}</a></td>
                        <td>{{ $technical->user->mobile }}</td>
                        <td>{{ $technical->student->national_code }}</td>
                        <td>{{ $technical->user->birth_date }}</td>
                        <td>{{ $technical->student->father_name }}</td>
                        @if ($technical->is_online_course)
                        <td>{{ $technical->onlineCourse->name }} <span class="text-info">(دوره آنلاین)</span></td>
                        @else
                        <td>{{ $technical->course->title }}</td>
                        @endif
                        <td>{{ $technical->branch->name }}</td>
                        <td><span class=" bg-label-warning">{{ $technical->courseRegister->secretary->full_name }}</span></td>
                        <td>{{ $technical->created_at }}</td>
                        <td>{{ $technical->paid_amount }}</td>
                        <td>
                            <div class=" ">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <span class="dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#technicalRegisterModal"
                                        wire:click="setTechnicalRegisterInfo({{ $technical->id }})">
                                        <i class="bx bx-dollar me-1 text-info"></i> ثبت پرداخت
                                    </span>
                                    <a class="dropdown-item" wire:click="updateStatusToIntroduced({{ $technical->id }})"><i class="text-success bx bx-check me-1"></i>معرفی شده</a>
                                    <a class="dropdown-item" wire:click="updateStatusToCancelled({{ $technical->id }})"><i class="text-danger bx bx-x me-1"></i>انصراف</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($technicals) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $technicals->links() }}</span>
        </div>
    </div>

    <!-- Modal for Technical Register -->
    <div class="modal " id="technicalRegisterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">پرداخت فنی حرفه ای</h5>
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
                    <button type="button" class="btn btn-primary" wire:click="storePayment({{ $technicalId }})" data-bs-dismiss="modal">ثبت</button>
                </div>
            </div>
        </div>
    </div>

</div>
