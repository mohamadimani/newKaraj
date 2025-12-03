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
            <h5 class="card-title col-ms-12"><span class=" col-md-8"><span>ثبت شماره</span></span></h5>
            <form>
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3 d-flex">
                        <label for="number" class="mb-3 col-md-2 font-13"> شماره</label>
                        <div class="col-md-10">
                            <input type="text" wire:model="number" id="number" class="form-control" placeholder="شماره">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 d-flex">
                        <label for="branch_id" class="mb-3 col-md-2 font-13"> شعبه</label>
                        <select wire:model="branch_id" id="branch_id" class="form-control">
                            <option value="">انتخاب کنید</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <span wire:click='phoneStore()' class="btn btn-success w-100 ">ثبت</span>
                    </div>
                </div>
            </form>
        </div>
        <h5 class="card-title col-ms-12 mt-4"><span class=" col-md-8"><span>شماره ها</span></span></h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>شماره</th>
                        <th>شعبه</th>
                        <th>شماره های داخلی</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                        <th>تاریخ ایجاد</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($phones as $key => $phone)
                        <tr>
                            <td>{{ calcIterationNumber($phones, $loop) }}</td>
                            <td>
                                @if ($phoneIdEdit and $phoneIdEdit == $phone->id)
                                    <input type="text" wire:model='number_edit'>
                                    <a wire:click='updatePhone' class="btn text-white btn-sm btn-success">ذخیره</a>
                                @else
                                    {{ $phone->number }}
                                @endif
                            </td>
                            <td>{{ $phone->branch->name }}</td>
                            <td><a wire:click='setInternalParentId({{ $phone->id }})' class="btn text-white btn-xs btn-info"><i class="bx bx-show "></i>&nbsp;<span>نمایش</span></a></td>
                            <td>
                                @if ($phone->is_active)
                                    <a class="btn text-white btn-sm btn-success" wire:click='updateStatus({{ $phone->id }},0)'>فعال</a>
                                @else
                                    <a class="btn text-white btn-sm btn-danger" wire:click='updateStatus({{ $phone->id }},1)'>غیرفعال</a>
                                @endif
                            </td>
                            <td>
                                <div class=" ">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu" style="">
                                        <a class="dropdown-item" wire:click='editPhone({{ $phone->id }})'><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                        <a class="dropdown-item" wire:click='deletePhone({{ $phone->id }})'><i class="bx bx-trash me-1"></i> حذف</a>
                                    </div>
                                </div>
                            </td>
                            <td> {{ \Verta::instance($phone->created_at)->format('%d %B %Y') }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($phones) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $phones->links() }}</span>
        </div>
    </div>
</div>
