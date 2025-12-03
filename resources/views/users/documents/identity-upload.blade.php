@extends('users.layouts.master')

@section('content')

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="h4   text-gray-800">بارگذاری مدارک هویتی</h4>
            </div>
            @include('users.layouts.alerts')
            <div class="row">
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
                        height: 200px !important;
                        cursor: pointer;
                        border-radius: 5px;
                        padding: 5px;
                        border: 1px solid silver;
                        margin-top: 10px;
                        box-shadow: 0 0 1px;
                    }
                </style>
                <form id="general-form-validation" class="card-body" enctype="multipart/form-data" action="{{ route('user.documents.identity-store') }}" method="POST">
                    @csrf
                    <div class="row g-3 d-flex mb-5">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="national_code">کد ملی</label>{{ requireSign() }}
                            <input type="tel" name="national_code" id="national_code" class="form-control" value="{{ old('national_code' , user()?->student?->national_code)  }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="father_name">نام پدر</label>{{ requireSign() }}
                            <input type="text" name="father_name" id="father_name" class="form-control" value="{{ old('father_name' , user()?->student?->father_name) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="birth_date">تاریخ تولد</label>{{ requireSign()  }}
                            <input data-jdp type="text" name="birth_date" id="birth_date" class="form-control" value="{{  old('birth_date' , user()?->birth_date)  }}">
                            @include('admin.layouts.jdp')
                        </div>
                    </div>
                    <div class="row g-3 d-flex">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="personal_image">{{ __('users.personal_image') }}</label>{{ requireSign() }} <sub>(حداکثر 4 مگابایت)</sub>
                            <input type="file" name="personal_image" id="personal_image" class="form-control">
                            @if(user()?->student?->personal_image)
                            <a href="{{ GetImage('students/personal/' . user()?->student?->personal_image) }}" target="_blank">
                                <img src="{{ GetImage('students/personal/' . user()?->student?->personal_image) }}" class="personal_image">
                            </a>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="id_card_image">{{ __('users.id_card_image') }}</label> <sub>(حداکثر 4 مگابایت)</sub>
                            <input type="file" name="id_card_image" id="id_card_image" class="form-control">
                            @if(user()->student?->id_card_image)
                            <a href="{{ GetImage('students/id-card/' . user()->student?->id_card_image) }}" target="_blank">
                                <img src="{{ GetImage('students/id-card/' . user()->student?->id_card_image) }}" class="id_card_image">
                            </a>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="birth_certificate_image">{{ __('users.birth_certificate_image') }}</label> <sub>(حداکثر 4 مگابایت)</sub>
                            <input type="file" name="birth_certificate_image" id="birth_certificate_image" class="form-control">
                            @if(user()->student?->birth_certificate_image)
                            <a href="{{ GetImage('students/birth-certificate/' . user()->student?->birth_certificate_image) }}" target="_blank">
                                <img src="{{ GetImage('students/birth-certificate/' . user()->student?->birth_certificate_image) }}" class="birth_certificate_image">
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="pt-4 text-end">
                            <button type="submit" class="btn btn-primary">ثبت</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection