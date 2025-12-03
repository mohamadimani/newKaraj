@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="p-5">
                <div class="text-center mb-4 mt-0 mt-md-n2">
                    <h3 class="secondary-font"> ثبت شعبه</h3>
                    <div class="row mb-4">
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.branches.index') }}" class="btn btn-info col-md-12 text-white">لیست شعبه ها</a>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger mb-5">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success  mb-5">
                        {{ Session::get('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.branches.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-sm-3 " for="name"><span>نام شعبه</span> {{ requireSign() }}</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ old('name') }}" name="name" id="name" placeholder="نام شعبه ...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="manager"><span>نام مدیر </span> </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ old('manager') }}" name="manager" id="manager" placeholder="نام مدیر ...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="province_id"><span>استان </span> </label>
                        <div class="col-sm-8">
                            <select name="province_id" id="province_id" class="form-control">
                                <option value="">انتخاب استان</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="address"><span>آدرس </span> </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ old('address') }}" name="address" id="address" placeholder="آدرس ...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="site"><span>آدرس سایت </span> </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ old('site') }}" name="site" id="site" placeholder="آدرس سایت ...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="bank_card_number"><span> شماره کارت بانکی </span> </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ old('bank_card_number') }}" name="bank_card_number" id="bank_card_number" placeholder="شماره کارت بانکی ...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="bank_card_name"><span> نام بانک کارت </span> </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ old('bank_card_name') }}" name="bank_card_name" id="bank_card_name" placeholder="نام بانک کارت  ...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="bank_card_owner"><span> نام صاحب کارت </span> </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ old('bank_card_owner') }}" name="bank_card_owner" id="bank_card_owner" placeholder="نام صاحب کارت  ...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="minimum_pay"><span>حداقل مبلغ پرداخت (تومان)</span></label>
                        <div class="col-sm-8">
                            <input type="number" id="minimum_pay" value="{{ old('minimum_pay') }}" name="minimum_pay" class="form-control" placeholder="حداقل مبلغ پرداخت...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 " for="online_pay_link"><span>لینک پرداخت آنلاین</span> </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ old('online_pay_link') }}" name="online_pay_link" id="online_pay_link" placeholder="لینک پرداخت آنلاین ...">
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 text-white">ثبت</button>
                        <a href="{{ route('admin.branches.index') }}" class="btn btn-label-secondary">انصراف</a>
                    </div>
                </form>

            </div>
        </div>

    </div>

@endsection
