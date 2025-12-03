
<div class="container-fluid flex-grow-1 container-p-y">
    @php
    use App\Enums\MarketingSms\TargetTypeEnum;
    @endphp    <div class="card mb-4">
        <h5 class="card-header heading-color">{{ __('marketing_sms_templates.create') }}</h5>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        <form id="marketing-sms-template-form-validation" class="card-body" action="{{ route('marketing-sms-templates.store') }}" method="POST">
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
                <div class="col-md-4 col-sm-6 mb-3">
                    <label class="form-label" for="title">{{ __('marketing_sms_templates.title') }}</label>
                    <input
                        name="title"
                        value="{{ old('title') }}"
                        type="text"
                        id="title"
                        class="form-control text-start"
                        placeholder="{{ __('marketing_sms_templates.title_placeholder') }}">
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <label class="form-label" for="target_type">{{ __('marketing_sms_templates.target_type') }}</label>
                    <select name="target_type" id="target_type" class="form-select">
                        <option value="">---</option>
                        @foreach (TargetTypeEnum::cases() as $targetType)
                        <option value="{{ $targetType->value }}" {{ old('target_type') == $targetType->value ? 'selected' : '' }}>{{ $targetType->getLabel() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <label class="form-label" for="branch">{{ __('marketing_sms_templates.branch') }}</label>
                    <select name="branch_id" id="branchId" wire:model="selectedBranchId" class="form-select select2" onchange="myFunction()">
                        <option value="">---</option>
                        @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6 mb-3"  wire:ignore>
                    <label class="form-label" for="profession">{{ __('marketing_sms_templates.profession') }}</label>
                    <select name="profession_ids[]" id="profession" class="form-select select2" multiple>
                        <option value="">---</option>
                        @foreach ($professions as $profession)
                        <option value="{{ $profession->id }}" {{ in_array($profession->id, old('profession_ids') ?? []) ? 'selected' : '' }}>{{ $profession->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="text-light small fw-semibold mb-3">{{ __('public.status') }}</div>
                    <label class="switch">
                        <input name="is_active" type="checkbox" class="switch-input" checked>
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="bx bx-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="bx bx-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">{{ __('public.status_active') }}</span>
                    </label>
                </div>
            </div>
            <div class="pt-4 text-end">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('marketing-sms-templates.index') }}">{{ __('public.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
        function myFunction() {
            const value = $('select#branchId').val();
            @this.setSelectedBranchId(value)
        }
        myFunction()
    </script>
    <script src="{{ asset('admin-panel/assets/js/validations/marketing-sms-template-form-validation.js') }}"></script>
</div>
