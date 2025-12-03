@extends('admin.layouts.master')
@php
use App\Models\CourseRegister;
use App\Enums\CourseReserve\StatusEnum;
@endphp
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    @if (Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success') }}
    </div>
    @endif
    <div class="card mb-4">
        <h5 class="card-header heading-color">{{ __('course_reserves.convert_to_course') }}</h5>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        <form id="general-form-validation" class="card-body" action="{{ route('course-reserves.convert-to-course') }}" method="POST">
            @csrf
            <div class="row g-3">
                @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li class="text-danger">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="col-md-4 col-sm-6">
                    <label class="form-label" for="clue">{{ __('course_registers.clue') }}</label>
                    <input name="clue_id" id="clue" class="form-control" value="{{ $courseReserve->clue->user->fullName }}" disabled>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label" for="phone-internal">مشاور</label>
                    <input name="clue_id" id="clue" class="form-control" value="{{ $courseReserve->secretary->user->full_name }}" disabled>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label" for="profession">{{ __('course_reserves.profession') }}</label>
                    <input
                        name="profession_id"
                        id="profession"
                        class="form-control"
                        value="{{ $courseReserve->profession->title }}"
                        disabled>
                </div>
            </div>
            <div class="row g-3 pt-3">
                <div class="col-md-4 col-sm-6 mb-1">
                    <label class="form-label" for="course">{{ __('course_registers.course') }}</label>
                    <select
                        name="course_id"
                        id="course-id"
                        class="select2 form-select"
                        data-allow-clear="true"
                        data-placeholder="{{ __('course_registers.select_course') }}">
                        <option value="">---</option>
                        @foreach($courses as $course)
                        <option
                            value="{{ $course->id }}"
                            {{ old('course_id') == $course->id ? 'selected' : '' }}
                            data-price="{{ $course->price }}">
                            {{ $course->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6 mb-1">
                    <label class="form-label" for="tuition-fee">{{ __('course_registers.tuition_fee') }}</label>
                    <input
                        name="tuition_fee"
                        value="{{ old('tuition_fee') }}"
                        type="text"
                        id="tuition-fee"
                        class="form-control text-start"
                        placeholder="{{ __('course_registers.tuition_fee_placeholder') }}"
                        disabled>
                </div>
                <div class="col-md-4 col-sm-6 mb-1">
                    <label class="form-label" for="course-register-description">{{ __('course_registers.course_register_description') }}</label>
                    <input
                        name="description"
                        value="{{ old('description') }}"
                        type="text"
                        id="course-register-description"
                        class="form-control text-start"
                        placeholder="{{ __('course_registers.course_register_description_placeholder') }}">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label" for="paid_amount">{{ __('course_reserves.already_paid_amount') }}</label>
                    <input
                        name="paid_amount"
                        id="paid_amount"
                        class="form-control"
                        value="{{ number_format($courseReserve->paid_amount) }}"
                        disabled>
                </div>
            </div>
            <div class="pt-4 text-end">
                <input type="hidden" name="course_reserve_id" value="{{ $courseReserve->id }}">
                <a
                    class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger"
                    href="{{ route('course-reserves.index') }}">
                    {{ __('public.cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
            </div>
        </form>
    </div>
    <div class="card mt-4">
        <h5 class="card-header heading-color">{!! __('course_reserves.user_reserved_courses', ['user' => "<b class='text-primary'>{$courseReserve->clue->user->fullName}</b>"]) !!}</h5>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('course_reserves.profession') }}</th>
                        <th>{{ __('course_reserves.paid_amount') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                    $i = 1;
                    @endphp

                    @foreach($clueCourseReserves as $courseReserve)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $courseReserve->profession->title }}</td>
                        <td class="text-success">{{ number_format($courseReserve->paid_amount) }}</td>
                        <td>
                            <span class="badge bg-label-{{ $courseReserve->status->getColor() }} me-1">
                                {{ $courseReserve->status->getLabel() }}
                            </span>
                        </td>
                        <td>
                            @if($courseReserve->status === StatusEnum::PENDING)
                            <button
                                type="button"
                                class="btn rounded-pill btn-icon btn-label-danger"
                                wire:click="cancelCourseReserve({{ $courseReserve->id }})">
                                <span
                                    class="tf-icons bx bx-window-close"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="{{ __('course_reserves.cancel_reserve') }}"></span>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(count($clueCourseReserves) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
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
    });
</script>
@endsection