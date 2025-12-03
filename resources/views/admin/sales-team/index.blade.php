@extends('admin.layouts.master')
@php
use App\Enums\Student\EducationEnum;
use App\Constants\PermissionTitle;
@endphp
@section('content')
<div class="container-fluid ">
    <div class="row justify-content-center p-0">
        <div class="col-lg-12 mt-4">
            <div class="card shadow">
                <div class="card-header p-2 d-flex justify-content-between align-items-center">
                    <h5> تیم های فروش</h5>
                    <a href="{{ route('sales-team.create') }}" class="btn btn-primary">ایجاد تیم فروش</a>
                </div>
                <div class="card-body p-2">
                    @include('admin.layouts.alerts')
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>نام تیم</th>
                                    <th>مدیر تیم</th>
                                    <th>اعضای تیم</th>
                                    <th>شعبه</th>
                                    <th>تارگت فروش ماهانه</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesTeams as $salesTeam)
                                <tr class="text-center">
                                    <td>{{ $salesTeam->title }}</td>
                                    <td>{{ $salesTeam->salesTeamManager->user->full_name ?? '-' }}</td>
                                    <td>
                                        @foreach($salesTeam->secretaries as $salesTeamSecretary)
                                        {{ $salesTeamSecretary->secretary->user->full_name }}<br>
                                        @endforeach
                                    </td>
                                    <td>{{ $salesTeam->branch->name ?? '-' }}</td>
                                    <td>{{ number_format($salesTeam->monthly_sale_target) }}</td>
                                    <td>
                                        @if($salesTeam->is_active)
                                        <span class="badge bg-success">فعال</span>
                                        @else
                                        <span class="badge bg-danger">غیرفعال</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('sales-team.edit', $salesTeam->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">هیچ تیم فروشی یافت نشد</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
