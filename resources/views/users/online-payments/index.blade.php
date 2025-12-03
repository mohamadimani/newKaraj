@php
    use App\Enums\OnlinePayment\StatusEnum;
@endphp

@extends('users.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <h4 class="mb-3">پرداخت های من</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>شناسه</th>
                        <th>شناسه سفارش</th>
                        <th>مبلغ</th>
                        <th>تاریخ</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td><a class="text-primary d-block" href="{{ route('user.orders.show', $payment->order->id) }}">{{ $payment->order->id }}</a></td>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ verta($payment->updated_at)->format('Y/m/d - H:i') }}</td>
                            <td>
                                @if ($payment->status == StatusEnum::PENDING->value)
                                    <span class="badge bg-label-warning">در انتظار پرداخت</span>
                                @elseif ($payment->status == StatusEnum::PAID->value)
                                    <span class="badge bg-label-success">پرداخت شده</span>
                                @elseif ($payment->status == StatusEnum::CANCELED->value)
                                    <span class="badge bg-label-danger">لغو شده</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">پرداختی یافت نشد!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
