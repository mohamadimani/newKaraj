@php
    use App\Enums\Discount\AmountTypeEnum;
    use App\Enums\Discount\DiscountTypeEnum;
@endphp
<div class="container-fluid flex-grow-1 container-p-y">

    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('discounts.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('discounts.create') }}">{{ __('discounts.create') }}</a>
        </div>
        <div class="card-body border-bottom">
        @include('admin.layouts.alerts')
            <div class="row">
                <div class="col-md-6">
                    <label for="search" class="form-label">{{ __('public.search') }}</label>
                    <input type="text" class="form-control" wire:model.live='search' placeholder="{{ __('discounts.search_placeholder') }}">
                </div>
                <div class="col-md-3">
                    <label for="amount_type" class="form-label">{{ __('discounts.amount_type') }}</label>
                    <select class="form-control" wire:model.live='amount_type'>
                        <option value="">---</option>
                        @foreach (AmountTypeEnum::cases() as $amount_type)
                            <option value="{{ $amount_type->value }}">{{ $amount_type->name() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="discount_type" class="form-label">{{ __('discounts.discount_type') }}</label>
                    <select class="form-control" wire:model.live='discount_type'>
                        <option value="">---</option>
                        @foreach (DiscountTypeEnum::cases() as $discount_type)
                            <option value="{{ $discount_type->value }}">{{ $discount_type->name() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>{{ __('discounts.title') }}</th>
                        <th>{{ __('discounts.code') }}</th>
                        <th>{{ __('discounts.amount') }}</th>
                        <th>{{ __('discounts.amount_type') }}</th>
                        <th>{{ __('discounts.discount_type') }}</th>
                        <th>{{ __('discounts.available_until') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.created_at') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($discounts as $key => $discount)
                        <tr>
                            <td>{{ calcIterationNumber($discounts, $loop) }}</td>
                            <td>{{ $discount->title }}</td>
                            <td>{{ $discount->code }}</td>
                            <td>{{ $discount->amount ? number_format($discount->amount) : '---' }}</td>
                            <td><span class="badge bg-label-{{ $discount->amount_type->color() }}">{{ $discount->amount_type->name() }}</span></td>
                            <td><span class="badge bg-label-{{ $discount->discount_type->color() }}">{{ $discount->discount_type->name() }}</span></td>
                            <td>{{ $discount->available_until ? Verta::instance($discount->available_until)->format('%d %B %Y') : '---' }}</td>
                            <td>
                                @if ($discount->is_active)
                                    <span class="badge bg-label-success me-1">فعال</span>
                                @else
                                    <span class="badge bg-label-danger me-1">غیرفعال</span>
                                @endif
                            </td>
                            <td>{{ $discount->created_at ? Verta::instance($discount->created_at)->format('%d %B %Y') : '---' }}</td>
                            <td>
                                <div class=" ">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if ($discount->is_active)
                                            <span class="text-left text-danger dropdown-item cursor-pointer" wire:click='changeStatus({{ $discount->id }},0)'>
                                                <i class="bx bx-x me-1"></i><span>{{ __('discounts.deactivate') }}</span>
                                            </span>
                                        @else
                                            <span class="text-left text-success dropdown-item cursor-pointer" wire:click='changeStatus({{ $discount->id }},1)'>
                                                <i class="bx bx-check me-1"></i><span>{{ __('discounts.activate') }}</span>
                                            </span>
                                        @endif
                                        <a class="dropdown-item cursor-pointer" href="{{ route('discounts.edit', $discount->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> {{ __('public.edit') }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (!count($discounts))
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $discounts->links() }}</span>
        </div>
    </div>
</div>
