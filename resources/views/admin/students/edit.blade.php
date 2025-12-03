@extends('admin.layouts.master')
@php
    use App\Enums\Student\EducationEnum;
    use App\Constants\PermissionTitle;
    use App\Models\CourseRegister;
@endphp
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header heading-color">{{ __('students.edit') }}</h5>
            @include('admin.layouts.alerts')
            <form id="general-form-validation" class="card-body" action="{{ route('students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="first-name">{{ __('users.first_name') }}</label>
                        <input name="first_name" value="{{ old('first_name', $student->user->first_name) }}" type="text" id="first-name" class="form-control text-start"
                            placeholder="{{ __('users.form.first_name_placeholder') }}">
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="last-name">{{ __('users.last_name') }}</label>
                        <input name="last_name" value="{{ old('last_name', $student->user->last_name) }}" type="text" id="last-name" class="form-control text-start"
                            placeholder="{{ __('users.form.last_name_placeholder') }}">
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="mobile">{{ __('users.mobile') }}</label>
                        <input name="mobile" value="{{ old('mobile', $student->user->mobile) }}" type="text" id="mobile" class="form-control"
                            placeholder="{{ __('users.form.mobile_placeholder') }}" style="direction: ltr;">
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="mobile2">{{ __('users.mobile2') }}</label>
                        <input name="mobile2" value="{{ old('mobile2', $student->user->mobile2) }}" type="text" id="mobile2" class="form-control"
                            placeholder="{{ __('users.form.mobile2_placeholder') }}">
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="national-code">{{ __('users.national_code') }}</label>
                        <input name="national_code" value="{{ old('national_code', $student->national_code) }}" type="text" id="national-code" class="form-control"
                            placeholder="{{ __('users.form.national_code_placeholder') }}">
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="father-name">{{ __('students.father_name') }}</label>
                        <input name="father_name" value="{{ old('father_name', $student->father_name) }}" type="text" id="father-name" class="form-control"
                            placeholder="{{ __('students.father_name_placeholder') }}">
                    </div>
                    <div class="col-md-1 col-sm-6 mb-3">
                        <label class="form-label" for="gender">{{ __('users.gender') }}</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="">---</option>
                            <option value="male" {{ old('gender', $student->user->gender) == 'male' ? 'selected' : '' }}>{{ __('users.gender_male') }}</option>
                            <option value="female" {{ old('gender', $student->user->gender) == 'female' ? 'selected' : '' }}>{{ __('users.gender_female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="province">{{ __('users.province') }}</label>
                        <select name="province_id" id="province" class="form-select">
                            <option value="">---</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}" {{ old('province_id', $student->user->province_id) == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="familiarity-ways">{{ __('clues.familiarity_ways') }}</label>
                        <select name="familiarity_way_id" id="familiarity-ways" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('clues.select_familiarity_ways') }}">
                            <option value="">---</option>
                            @foreach ($familiarityWays as $familiarityWay)
                                <option value="{{ $familiarityWay->id }}" {{ old('familiarity_way_id', $student?->user?->clue?->familiarity_way_id) == $familiarityWay->id ? 'selected' : '' }}>
                                    {{ $familiarityWay->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="professions">{{ __('clues.favorite_professions') }}</label>
                        <select name="profession_ids[]" id="professions" class="select2 form-select" multiple data-allow-clear="true" data-placeholder="{{ __('clues.select_professions') }}">
                            <option value="">---</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}"
                                    {{ old('profession_ids', $student?->user?->clue?->professions()->pluck('id')->toArray()) !== null &&
                                    in_array($profession->id, old('profession_ids', $student->user->clue->professions()->pluck('id')->toArray()))
                                        ? 'selected'
                                        : '' }}>
                                    {{ $profession->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="birth-date">{{ __('students.birth_date') }}</label>
                        <input name="birth_date" value="{{ old('birth_date', $student->user->birth_date) }}" type="text" id="birth-date" class="form-control dob-picker"
                            placeholder="{{ __('students.birth_date_placeholder') }}">
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="education">{{ __('students.education') }}</label>
                        <select name="education" id="education" class="form-select">
                            <option value="">---</option>
                            @foreach (EducationEnum::cases() as $education)
                                <option value="{{ $education->value }}" {{ old('education', $student?->education?->value) == $education->value ? 'selected' : '' }}>{{ $education->getLabel() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="phone">{{ __('students.phone') }}</label>
                        <input name="phone" value="{{ old('phone', $student->user->phone) }}" type="text" id="phone" class="form-control"
                            placeholder="{{ __('students.phone_placeholder') }}">
                    </div>
                </div>
                <div class="pt-4 text-end">
                    <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('students.index') }}">{{ __('public.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
                </div>
            </form>
        </div>

        <div class="card mb-4">
            <style>
                sub {
                    color: rgb(242, 94, 94);
                }

                .personal_image:hover {
                    scale: 1.3;
                    transition: all 0.3s ease-in-out;
                }

                .id_card_image:hover {
                    scale: 1.3;
                    transition: all 0.3s ease-in-out;
                }

                .birth_certificate_image:hover {
                    scale: 1.3;
                    transition: all 0.3s ease-in-out;
                }

                .personal_image,
                .id_card_image,
                .birth_certificate_image {
                    max-width: 300px !important;
                    height: 200px !important;
                    cursor: pointer;
                    border-radius: 5px;
                    padding: 5px;
                    border: 1px solid silver;
                    margin-top: 10px;
                    box-shadow: 0 0 1px;
                }
            </style>
            <h5 class="card-header heading-color">آپلود مدارک</h5>
            <form id="general-form-validation" class="card-body" enctype="multipart/form-data" action="{{ route('students.upload-images', ['student' => $student->id]) }}" method="POST">
                @csrf
                <div class="row g-3 d-flex">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="personal_image">{{ __('users.personal_image') }}</label>{{ requireSign() }} <sub>(حداکثر 4 مگابایت)</sub>
                        <input type="file" name="personal_image" id="personal_image" class="form-control">
                        @if ($student->personal_image)
                            <a href="{{ GetImage('students/personal/' . $student->personal_image) }}" target="_blank">
                                <img src="{{ GetImage('students/personal/' . $student->personal_image) }}" class="personal_image">
                            </a>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="id_card_image">{{ __('users.id_card_image') }}</label> <sub>(حداکثر 4 مگابایت)</sub>
                        <input type="file" name="id_card_image" id="id_card_image" class="form-control">
                        @if ($student->id_card_image)
                            <a href="{{ GetImage('students/id-card/' . $student->id_card_image) }}" target="_blank">
                                <img src="{{ GetImage('students/id-card/' . $student->id_card_image) }}" class="id_card_image">
                            </a>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="birth_certificate_image">{{ __('users.birth_certificate_image') }}</label> <sub>(حداکثر 4 مگابایت)</sub>
                        <input type="file" name="birth_certificate_image" id="birth_certificate_image" class="form-control">
                        @if ($student->birth_certificate_image)
                            <a href="{{ GetImage('students/birth-certificate/' . $student->birth_certificate_image) }}" target="_blank">
                                <img src="{{ GetImage('students/birth-certificate/' . $student->birth_certificate_image) }}" class="birth_certificate_image">
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row g-3">
                    <div class="pt-4 text-end">
                        <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- student course registers -->
        <div class="card mt-4">
            <div class="align-items-center card-header d-flex justify-content-between">
                <span class="font-20 fw-bold heading-color">
                    {!! __('course_registers.user_registered_courses', ['user' => '<b class="text-primary">' . $student->user->fullName]) . '</b>' !!}
                </span>
                <a class="btn btn-info" href="{{ route('course-registers.create', ['clue_id' => $student->user->clue->id, 'back_url' => route('students.edit', $student->id)], false) }}">
                    {{ __('course_registers.create') }}
                </a>
            </div>
            <div class="table-responsive text-nowrap  mb-2">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('course_registers.course') }}</th>
                            <th>پرداختی ها <sub>(تومان)</sub> </th>
                            <th>بدهی ها <sub>(تومان)</sub> </th>
                            <th>توضیحات</th>
                            <th>{{ __('course_registers.first_register') }}</th>
                            <th>{{ __('public.status') }}</th>
                            <th>{{ __('public.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($student->courseRegisters as $courseRegister)
                            <tr>
                                <td>
                                    <a href="{{ route('courses.course-students', $courseRegister->course->id) }}">
                                        {{ $courseRegister->course->title }}
                                    </a>
                                </td>
                                <td class="text-success">{{ number_format($courseRegister->paid_amount) }}</td>
                                <td class="text-danger">
                                    @php
                                        $remainingAmount = $courseRegister->debt();
                                    @endphp
                                    @if ($remainingAmount > 0)
                                        {{ number_format($remainingAmount) }}
                                    @else
                                        <span class="badge bg-label-info">{{ __('payments.settled') }}</span>
                                    @endif
                                </td>
                                <td>{{ $courseRegister->amount_description }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $courseRegister->is_first_register ? 'success' : 'danger' }}">
                                        {{ $courseRegister->is_first_register ? __('course_registers.is_first_register') : __('course_registers.is_not_first_register') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $courseRegister->status->getColor() }} me-1">
                                        {{ $courseRegister->status->getLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <div class=" ">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <span class="dropdown-item cursor-pointer add-payment-button" data-paymentable-id="{{ $courseRegister->id }}"
                                                data-course-id="{{ $courseRegister->course_id }}" data-remaining-amount="{{ $remainingAmount }}" data-bs-toggle="modal"
                                                data-bs-target="#addNewCCModal">
                                                <i class="bx bx-dollar me-1"></i> {{ __('course_registers.add_payment') }}
                                            </span>
                                            <a href="{{ route('payments.index', ['course_register_id' => $courseRegister->id, 'back_url' => route('course-registers.create', ['clue_id' => $courseRegister->student->user->clue->id, 'back_url' => request()->query('back_url')], false)]) }}"
                                                class="dropdown-item">
                                                <i class="tf-icons fa-solid fa-money-bill"></i> {{ __('payments.page_title') }}
                                            </a>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if (count($student->courseRegisters) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('admin-panel/assets/js/validations/clue-form-validation.js') }}"></script>
    <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4 mt-0 mt-md-n2">
                        <h3 class="secondary-font">{{ __('payments.add_payment') }}</h3>
                    </div>
                    <form id="addNewCCForm" class="row g-3" action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 pt-3">
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="course">{{ __('course_registers.course') }}</label>
                                <select name="course_id" id="payment-course-id" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_registers.select_course') }}">
                                    <option value="">---</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="remaining-amount">{{ __('payments.remaining_amount') }}</label>
                                <input name="remaining_amount" value="" type="text" id="remaining-amount" class="form-control text-start">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="paid-amount">{{ __('course_registers.paid_amount') }}</label>
                                <input name="paid_amount" value="{{ old('paid_amount') }}" type="text" id="paid-amount" class="form-control text-start"
                                    placeholder="{{ __('course_registers.paid_amount_placeholder') }}">
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="pay-date">{{ __('course_registers.pay_date') }}</label>
                                <input name="pay_date" type="text" id="pay-date" class="form-control dob-picker" placeholder="{{ __('course_registers.pay_date_placeholder') }}"
                                    value="{{ old('pay_date') }}">
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="payment-method">{{ __('course_registers.payment_method') }}</label>
                                <select name="payment_method_id" id="payment-method" class="select2 form-select" data-allow-clear="true"
                                    data-placeholder="{{ __('course_registers.select_payment_method') }}">
                                    <option value="">---</option>
                                    @foreach ($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="payment-description">{{ __('course_registers.payment_description') }}</label>
                                <input name="payment_description" value="{{ old('payment_description') }}" type="text" id="payment-description" class="form-control text-start"
                                    placeholder="{{ __('course_registers.payment_description_placeholder') }}">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12 col-sm-6 mb-3">
                                <label class="form-label" for="paid_image">تصویر فیش پرداخت </label> {{ requireSign() }}
                                <input name="paid_image" type="file" id="paid_image" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <input type="hidden" name="paymentable_id" id="paymentable_id" value="">
                            <input type="hidden" name="paymentable_type" value="{{ CourseRegister::class }}">
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                {{ __('public.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('public.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#course-id').on('select2:select', function(e) {
                const price = e.params.data.element.dataset.price;
                $('#tuition-fee').val(new Intl.NumberFormat().format(price));
                $('#tuition-fee').prop('disabled', true);
            });
            const selectedCourseId = $('#course-id').val();
            if (selectedCourseId) {
                const selectedCourseElement = $('#course-id').find('option:selected');
                const price = selectedCourseElement.data('price');
                $('#tuition-fee').val(new Intl.NumberFormat().format(price));
                $('#tuition-fee').prop('disabled', true);
            }
            $('.add-payment-button').on('click', function() {
                $('#paymentable_id').val($(this).data('paymentable-id'));
                $('#payment-course-id option[value="' + $(this).data('course-id') + '"]').prop('selected', true);
                $('#select2-payment-course-id-container').text($('#payment-course-id option:selected').text());
                $('#payment-course-id').prop('disabled', true);
                $('#remaining-amount').val(new Intl.NumberFormat().format($(this).data('remaining-amount')));
                $('#remaining-amount').prop('disabled', true);
            });
            $('#register-paid-amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                }
            });
            $('#paid-amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                }
            });
        });
    </script>
@endsection
