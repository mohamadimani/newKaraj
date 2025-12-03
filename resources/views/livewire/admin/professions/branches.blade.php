<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card p-3">
        @if (Session::has('success'))
            <div class="alert alert-success  mb-5 text-center">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger  mb-5 text-center">
                {{ Session::get('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger ">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li class="font-13">* {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-header border-bottom">
            <h5 class="card-title col-ms-12">
                <div class=" col-md-12">
                    شعبه های حرفه : {{ $selectedProfession->title }}
                    <span wire:click='$set("showBranches",null)' class="btn btn-xs btn-secondary float-end">
                        <i class="bx bx-arrow-back"></i>
                        بازگشت
                    </span>
                </div>
            </h5>
            <div class="row">
                <div class="col-md-12 mb-3 d-flex">
                    <div class="col-md-2 m-2" wire:ignore>
                        <label for="branch_id">{{ __('professions.branches') }}</label>
                        <select wire:model="branch_id" id="branch_id" class="form-control select2" required>
                            <option value="">انتخاب کنید</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 m-2">
                        <label for="create">&nbsp;</label>
                        <span wire:click='storeBranch({{ $selectedProfession->id }})' class="btn btn-success w-100 ">ثبت</span>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mb-5 mt-5">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>شعبه</th>
                    <th>حذف</th>
                    <th>تاریخ ایجاد</th>
                </tr>
            </thead>
            <tbody class="">
                @if ($selectedProfession && count($selectedProfession->branches))
                    @foreach ($selectedProfession->branches as $key => $branch)
                        <tr>
                            <td>{{ calcIterationNumber($selectedProfession , $loop) }}</td>
                            <td>{{ $branch->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-danger" wire:click='deleteBranch({{ $selectedProfession->id }},{{ $branch->id }})'>
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                            <td>{{ \Verta::instance($branch->created_at)->format('%d %B %Y') }} </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">
                            {{ __('messages.empty_table') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
