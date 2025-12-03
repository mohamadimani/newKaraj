@extends('users.layouts.master')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row gy-4">
            <div class="col-xl-4 col-lg-5 col-md-5 order-0 order-md-0">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded "
                                    src="{{ user()?->student?->personal_image ? GetImage('students/personal/' . user()->student->personal_image) : asset('admin-panel/assets/img/logo/logo.jfif') }}"
                                    height="110" width="110" alt="User avatar">
                                <div class="user-info text-center">
                                    <h5 class="mb-2">{{ user()->getFullNameAttribute() }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class=" justify-content-around flex-wrap my-4 py-3">
                            <div class="d-flex gap-2">
                                <span class=""><i class="bx bx-dollar "></i></span>
                                <h6 class="mb-0"><span>کیف پول</span> : </h6>
                                <h5 class="mb-0"><span class="badge bg-label-success">{{ number_format(user()->wallet) }}</span></h5>
                                <h6 class="mb-0"><sub>تومان</sub></h6>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <span class="  "><i class="bx bx-buildings "></i></span>
                                <h6 class="mb-0">
                                    <span>دوره های حضوری شرکت کرده</span> :
                                    {{ user()->student?->courseRegisters?->count() ?? 0 }}
                                </h6>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <span class="  "><i class="fa fa-tv  "></i></span>
                                <h6 class="mb-0">
                                    <span>دوره های آنلاین شرکت کرده</span> :
                                    {{ user()->orderItems?->count() ?? 0 }}
                                </h6>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <span class=" "><i class="bx bx-check "></i></span>
                                <h6 class="mb-0"> <span>گواهی کسب شده</span> : 0
                                </h6>
                            </div>
                        </div>

                        <hr>

                        <h5 class="pb-2  mb-4 secondary-font">اطلاعات کاربری</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="fw-bold me-2">موبایل:</span>
                                    <span class="d-inline-block" dir="ltr">{{ user()->mobile }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="fw-bold me-2">استان:</span>
                                    <span>{{ user()->province?->name ?? 'نامشخص' }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="fw-bold me-2">کد ملی:</span>
                                    <span>{{ user()->student?->national_code ?? 'نامشخص' }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-center pt-3">
                                {{-- <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#editUser" data-bs-toggle="modal">ویرایش</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7 col-md-7 order-1 order-md-1">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">دوره های حضوری من</h5>
                    </div>
                    <div class="table-responsive mb-3">
                        <table class="table datatable-project border-top">
                            <thead>
                                <tr class="text-center">
                                    <th>نام دوره</th>
                                    <th>تاریخ ثبت نام</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (user()->student?->courseRegisters?->count() > 0)
                                    @foreach (user()->student?->courseRegisters as $courseRegister)
                                        <tr class="text-center">
                                            <td>{{ $courseRegister->course?->title }}</td>
                                            <td>{{ verta($courseRegister->created_at)->format('Y/m/d') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">دوره ای برای شما ثبت نشده است</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">دوره های آنلاین من</h5>
                    </div>
                    <div class="table-responsive mb-3">
                        <table class="table datatable-project border-top">
                            <thead>
                                <tr class="text-center">
                                    <th>نام دوره</th>
                                    <th>تاریخ ثبت نام</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (user()->orderItems?->count() > 0)
                                    @foreach (user()->orderItems->where('license_key', '!=', null) as $orderItem)
                                        <tr class="text-center">
                                            <td>{{ $orderItem->onlineCourse?->name }}</td>
                                            <td>{{ verta($orderItem->created_at)->format('Y/m/d') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">دوره ای برای شما ثبت نشده است</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header mb-3">
                        <h5 class="mb-0">کلاس های این هفته</h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline">
                            @if (user()->student?->courseRegisters?->count() > 0)
                                @foreach (user()?->student?->courseRegisters as $courseRegister)
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-primary"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-1">
                                                <h6 class="mb-0 mt-n1">{{ $courseRegister->course?->title }}</h6>
                                                {{-- <small class="text-muted mt-1 mt-sm-0 mb-1 mb-sm-0">{{ $courseRegister->course?->title }}</small> --}}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                <li class="timeline-end-indicator">
                                    <i class="bx bx-check-circle"></i>
                                </li>
                            @else
                                <li class="timeline-end-indicator">
                                    <span colspan="5" class="text-center">دوره ای برای شما ثبت نشده است</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">پیامک های دریافتی من</h5>
                    </div>
                    <div class="table-responsive mb-3">
                        <table class="table datatable-project border-top">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>متن</th>
                                    <th class="text-nowrap">تاریخ ارسال</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (user()?->sms?->count() > 0)
                                    @foreach (user()?->sms as $sms)
                                        <tr>
                                            <td>{{ $sms->text }}</td>
                                            <td>{{ $sms->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">پیامکی برای شما ثبت نشده است</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4 mt-0 mt-md-n2">
                            <h3 class="secondary-font">ویرایش اطلاعات </h3>
                        </div>
                        <form id="editUserForm" class="row g-3" onsubmit="return false">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserFirstName">نام</label>
                                <input type="text" name="modalEditUserFirstName" class="form-control" placeholder="">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserLastName">نام خانوادگی</label>
                                <input type="text" name="modalEditUserLastName" class="form-control" placeholder="">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserNationalCode">کد ملی</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" name="nationalCode" class="form-control phone-number-mask text-start" placeholder="" dir="ltr">
                                    <span class="input-group-text" dir="ltr"></span>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">ثبت</button>
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                                    انصراف
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
