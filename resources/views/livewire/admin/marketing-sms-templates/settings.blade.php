@php
use App\Enums\MarketingSms\TargetTypeEnum;
@endphp

<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{!! __('marketing_sms_templates.settings_dynamic_title', [
                'title' => '<span class="fw-bold text-primary">' . $marketingSmsTemplate->title . '</span>'
                ]) !!}</span>
            <span>
                <a class="btn me-sm-3 me-1 btn-info" href="{{ route('marketing-sms-items.create', $marketingSmsTemplate->id) }}">{{ __('marketing_sms_templates.create_settings') }}</a>
                <a class="btn btn-label-secondary btn-outline-danger" href="{{ route('marketing-sms-templates.index') }}">{{ __('public.return') }}</a>
            </span>
        </div>
        <div class="card-body row">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label" for="search">{{ __('public.search') }}</label>
                    <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="{{ __('marketing_sms_templates.search_placeholder_settings') }}">
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('marketing_sms_templates.after_time') }}</th>
                        <th>{{ __('marketing_sms_templates.content') }}</th>
                        <th>{{ __('public.created_at') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($templateItems as $index => $templateItem)
                    <tr>
                        <td>{{ $templateItems->firstItem() + $index }}</td>
                        <td>{{ secondsToTimeString($templateItem->after_time) }}</td>
                        <td>
                            <span class="text-start " style="white-space: pre-wrap;">{{ $templateItem->content }}</span>
                        </td>
                        <td>{{ georgianToJalali($templateItem->created_at, true) }}</td>
                        <td>
                            @if ($templateItem->is_active)
                            <span class="badge bg-label-success me-1">
                                {{ __('public.status_active') }}
                            </span>
                            @else
                            <span class="badge bg-label-danger me-1">
                                {{ __('public.status_inactive') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('marketing-sms-items.edit', $templateItem->id) }}"><i class="bx bx-edit-alt me-1"></i> {{ __('public.edit') }}</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($templateItems->count() === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
            <div class="p-3">
                <span class="d-block mt-3"></span>{{ $templateItems->links() }}</span>
            </div>
        </div>
    </div>
</div>
