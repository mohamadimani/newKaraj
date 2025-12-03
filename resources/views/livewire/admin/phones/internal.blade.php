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
                <span class=" col-md-8"><span>ثبت داخلی برای</span> <span class="text-info">({{ $phoneModel->number }})</span>
                    <a wire:click='unSetInternalParentId()' class="btn text-white btn-sm btn-info float-end">بازگشت</a>
                </span>
            </h5>
            <form>
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3 d-flex">
                        <label for="title" class="mb-3 col-md-2 font-13"><span>عنوان (نام مستعار)</span>{{ requireSign() }}</label>
                        <div class="col-md-10">
                            <input type="text" wire:model="title" id="title" class="form-control" placeholder="عنوان">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 d-flex">
                        <label for="number" class="mb-3 col-md-2 font-13"> شماره داخلی{{ requireSign() }}</label>
                        <div class="col-md-10">
                            <input type="text" wire:model="number" id="number" class="form-control" placeholder="شماره">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <span wire:click='internalStore()' class="btn btn-success w-100 ">ثبت</span>
                    </div>
                </div>
            </form>
        </div>
        <h5 class="card-title col-ms-12 mt-4"><span class=" col-md-8"><span>داخلی ها</span></span></h5>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>شماره</th>
                    <th>عنوان (نام مستعار)</th>
                    <th>شعبه</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                    <th>تاریخ ایجاد</th>
                </tr>
            </thead>
            <tbody class="">
                @foreach ($internalNumbers as $key => $internalNumber)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    @if ($internalNumberIdEdit and $internalNumberIdEdit == $internalNumber->id)
                    <td>
                        <input type="text" wire:model='number_edit'>
                    </td>
                    <td><input type="text" wire:model='title_edit'>
                        <a wire:click='updateInternal' class="btn text-white btn-sm btn-success">ذخیره</a>
                    </td>
                    @else
                    <td>{{ $internalNumber->number }}</td>
                    <td>{{ $internalNumber->title }}</td>
                    @endif
                    <td>{{ $internalNumber->phone->branch->name }}</td>
                    <td>
                        @if ($internalNumber->is_active)
                        <a class="btn text-white btn-sm btn-success" wire:click='updateStatus({{ $internalNumber->id }},0)'>فعال</a>
                        @else
                        <a class="btn text-white btn-sm btn-danger" wire:click='updateStatus({{ $internalNumber->id }},1)'>غیرفعال</a>
                        @endif
                    </td>
                    <td>
                        <div class=" ">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="">
                                <a class="dropdown-item" wire:click='editInternal({{ $internalNumber->id }})'><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                <a class="dropdown-item" wire:click='deleteInternal({{ $internalNumber->id }})'><i class="bx bx-trash me-1"></i> حذف</a>
                            </div>
                        </div>
                    </td>
                    <td> {{ \Verta::instance($internalNumber->created_at)->format('%d %B %Y') }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if(count($internalNumbers) === 0)
        <div class="text-center py-5">
            {{ __('messages.empty_table') }}
        </div>
        @endif
    </div>
</div>
