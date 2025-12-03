<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title text-primary">لیست آدرس های فنی حرفه ای</h5>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createTechnicalAddressModal">
                                    ایجاد آدرس جدید
                                </button>
                            </div>
                            <div class="table-responsive text-nowrap">
                                <div class="mb-3 col-md-3">
                                    <div class="input-group ">
                                        <input wire:model.live="search" type="text" class="form-control" placeholder="جستجو..." name="search" value="{{ request('search') }}">
                                    </div>
                                </div>
                                @include('admin.layouts.alerts')
                                @if ($technicalAddresses->isEmpty())
                                    <div class="alert alert-warning text-center" role="alert">
                                        هیچ آدرسی یافت نشد
                                    </div>
                                @else
                                    <table class= "table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>عنوان</th>
                                                <th>آدرس</th>
                                                <th>تلفن</th>
                                                <th>شعبه</th>
                                                <th>استان</th>
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            @foreach ($technicalAddresses as $address)
                                                <tr>
                                                    @if ($technicalAddress_edit == $address->id)
                                                        <td>
                                                            <input type="text" class="form-control" wire:model="title_edit">

                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" wire:model="address_edit"></textarea>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" wire:model="phone_edit">
                                                        </td>
                                                        <td>
                                                            <select class="form-select" wire:model="branch_id_edit">
                                                                <option value="">انتخاب کنید</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-select" wire:model="province_id_edit">
                                                                <option value="">انتخاب کنید</option>
                                                                @foreach ($provinces as $province)
                                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                                @endforeach
                                                            </select>

                                                        </td>
                                                    @else
                                                        <td>{{ $address->title }}</td>
                                                        <td>{{ $address->address }}</td>
                                                        <td>{{ $address->phone }}</td>
                                                        <td>{{ $address->branch->name }}</td>
                                                        <td>{{ $address->province->name }}</td>
                                                    @endif
                                                    <td>
                                                        @if ($technicalAddress_edit != $address->id)
                                                            <span wire:click="edit({{ $address->id }})" class="btn btn-sm btn-primary">ویرایش</span>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-success" wire:click="update({{ $address->id }})">ذخیره</button>
                                                        @endif
                                                        <span wire:click="deleteAddress({{ $address->id }})" class="btn btn-sm btn-danger">حذف</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Technical Address Modal -->
        <div class="modal fade" id="createTechnicalAddressModal" tabindex="-1" aria-labelledby="createTechnicalAddressModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTechnicalAddressModalLabel">ایجاد آدرس جدید</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان</label>
                            <input type="text" class="form-control" id="title" name="title" required wire:model="title">
                        </div>
                        <div class="mb-3">
                            <label for="province_id" class="form-label">استان</label>
                            <select class="form-select" id="province_id" name="province_id" required wire:model="province_id">
                                <option value="">انتخاب کنید</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">آدرس</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required wire:model="address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">تلفن</label>
                            <input type="text" class="form-control" id="phone" name="phone" required wire:model="phone">
                        </div>
                        <div class="mb-3">
                            <label for="branch_id" class="form-label">شعبه</label>
                            <select class="form-select" id="branch_id" name="branch_id" required wire:model="branch_id">
                                <option value="">انتخاب کنید</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button   class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                        <button   class="btn btn-primary" wire:click="store" data-bs-dismiss="modal">ذخیره</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
