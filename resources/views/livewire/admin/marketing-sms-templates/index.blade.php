@php
use App\Enums\MarketingSms\TargetTypeEnum;
@endphp

<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('marketing_sms_templates.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('marketing-sms-templates.create') }}">{{ __('marketing_sms_templates.create') }}</a>
        </div>
        <div class="card-body row">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label" for="search">{{ __('public.search') }}</label>
                    <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="{{ __('marketing_sms_templates.search_placeholder') }}">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label" for="branch">{{ __('marketing_sms_templates.branch') }}</label>
                    <select wire:model.live="filterBranchId" id="branch" class="form-select">
                        <option value="">---</option>
                        @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $filterBranchId==$branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="target_type">{{ __('marketing_sms_templates.target_type') }}</label>
                    <select id="target_type" class="form-select" wire:model.live="filterTargetType">
                        <option value="">---</option>
                        @foreach (TargetTypeEnum::cases() as $targetType)
                        <option value="{{ $targetType->value }}" {{ $filterTargetType==$targetType->value ? 'selected' : '' }}>{{ $targetType->getLabel() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('marketing_sms_templates.title') }}</th>
                        <th>{{ __('marketing_sms_templates.profession') }}</th>
                        <th>{{ __('marketing_sms_templates.branch') }}</th>
                        <th>{{ __('marketing_sms_templates.target_type') }}</th>
                        <th>{{ __('public.created_at') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($templates as $template)
                    <tr>
                        <td>{{ calcIterationNumber($templates, $loop) }}</td>
                        <td>{{ $template->title }}</td>
                        <td>
                            @if(count($template->professions) > 0)
                            @foreach($template->professions as $key => $profession)
                            <span class="badge bg-label-primary">{{ $profession->title }}</span>
                            @if(($key%5) >=4)
                            <br>
                            @endif
                            @endforeach
                            @else
                            ---
                            @endif
                        </td>
                        <td>{{ $template->branch->name ?? '---' }}</td>
                        <td>
                            <span class="badge bg-label-{{ $template->target_type->getColor() }} me-1">
                                {{ $template->target_type->getLabel() }}
                            </span>
                        </td>
                        <td>{{ georgianToJalali($template->created_at, true) }}</td>
                        <td>
                            @if ($template->is_active)
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
                                    <a class="dropdown-item" href="{{ route('marketing-sms-templates.settings', $template->id) }}"><i class="bx bx-cog me-1"></i> {{
                                        __('marketing_sms_templates.settings') }}</a>
                                    <a class="dropdown-item" href="{{ route('marketing-sms-templates.edit', $template->id) }}"><i class="bx bx-edit-alt me-1"></i> {{ __('public.edit') }}</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($templates) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $templates->links() }}</span>
        </div>
    </div>
</div>