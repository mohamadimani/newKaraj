@extends('admin.layouts.master')
@php
use App\Enums\Student\EducationEnum;
use App\Constants\PermissionTitle;
@endphp
@section('content')
<div class="container-fluid p-4 ">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header">
                    <h5>ویرایش تیم فروش
                        <span class="text-info">
                            {{ $salesTeam->title }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    @include('admin.layouts.alerts')
                    <form action="{{ route('sales-team.update', $salesTeam) }}" method="POST">
                        @csrf
                            @method('PATCH')
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="title" class="form-label">نام تیم <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $salesTeam->title) }}" required placeholder="نام تیم را وارد کنید">
                                </div>

                                <div class="col-md-6">
                                    <label for="sales_team_manager_id" class="form-label">مدیر تیم <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="sales_team_manager_id" name="sales_team_manager_id" required data-placeholder="لطفا مدیر تیم را انتخاب کنید">
                                        <option value=""></option>
                                        @foreach($secretaries as $secretary)
                                        <option value="{{ $secretary->id }}" {{ $salesTeam->sales_team_manager_id == $secretary->id ? 'selected' : '' }}>{{ $secretary->user->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="branch_id" class="form-label">شعبه <span class="text-danger">*</span></label>
                                    <select class="form-select" id="branch_id" name="branch_id" required>
                                        <option value="">لطفا شعبه را انتخاب کنید</option>
                                        @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $salesTeam->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="monthly_sale_target" class="form-label">تارگت فروش ماهانه </label>
                                    <input type="number" class="form-control" id="monthly_sale_target" name="monthly_sale_target"
                                        value="{{ old('monthly_sale_target', $salesTeam->monthly_sale_target) }}" min="0" placeholder="مقدار تارگت فروش را وارد کنید">
                                </div>
                                <div class="col-md-6">
                                    <label for="secretaries" class="form-label">مشاورین <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="secretaries" name="secretaries[]" multiple data-placeholder="مشاورین را انتخاب کنید">
                                        @foreach($secretaries as $secretary)
                                        <option value="{{ $secretary->id }}" {{ in_array($secretary->id, $salesTeam->secretaries->pluck('secretary_id')->toArray()) ? 'selected' : '' }}>{{
                                            $secretary->user->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="description" class="form-label">توضیحات</label>
                                    <input class="form-control" id="description" name="description" value="{{ old('description', $salesTeam->description) }}" placeholder="توضیحات را وارد کنید">
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <a href="{{ route('sales-team.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>انصراف
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>ویرایش
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection