<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card p-2">
        <div class="card-header border-bottom">
            <h5 class="card-title">فیلتر جستجو شعبه</h5>

            <div class="row">
                <div class="col-md-4">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="جستجوی نام و آدرس و شماره کارت و یا مدیر شعبه ">
                </div>
                <div class="col-md-6"></div>
                <a href="{{ route('admin.branches.create') }}" class="btn btn-info col-md-2 text-white">ایجاد شعبه </a>
            </div>
        </div>

        @if (Session::has('success'))
            <div class="alert alert-success  text-center  mb-5">
                <h5 class="text-success m-0">{{ Session::get('success') }}</h5>
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger  text-center  mb-5">
                <h5 class="text-danger m-0">{{ Session::get('error') }}</h5>
            </div>
        @endif

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <caption class="ms-4">{{ $branches->links() }}</caption>
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>نام مدیر</th>
                        <th>استان</th>
                        <th>آدرس</th>
                        <th>تلفن</th>
                        <th>حداقل پرداخت</th>
                        <th>وضعیت</th>
                        <th>تاریخ ایجاد</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($branches as $key => $branch)
                        <tr>
                            <td>{{ calcIterationNumber($branches, $loop) }}</td>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->manager }}</td>
                            <td>{{ $branch->province?->name }}</td>
                            <td>{{ $branch->address }}</td>
                            <td>{{ $branch->phones()->pluck('number')->implode(',') }}</td>
                            <td>{{ number_format($branch->minimum_pay) }}</td>
                            <td>
                                @if ($branch->is_active)
                                    <span class="badge bg-label-success me-1">فعال</span>
                                @else
                                    <span class="badge bg-label-danger me-1">غیر فعال</span>
                                @endif
                            </td>
                            <td> {{ \Verta::instance($branch->created_at)->format('%B %d %Y') }} </td>
                            <td>
                                <div class="demo-inline-spacing">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end font-14">
                                            <li>
                                                <span class="text-left text-info dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#branchModal{{ $branch->id }}">
                                                    <i class="bx bx-show me-1"></i><span>جزئیات</span>
                                                </span>
                                            </li>
                                            <li>
                                                @if ($branch->is_active)
                                                    <span class="text-left text-warning dropdown-item cursor-pointer" wire:click='changeStatus({{ $branch->id }},0)'>
                                                        <i class="bx bx-x me-1"></i><span>غیر فعال</span>
                                                    </span>
                                                @else
                                                    <span class="text-left text-success dropdown-item" wire:click='changeStatus({{ $branch->id }},1)'>
                                                        <i class="bx bx-check me-1"></i><span>فعال</span>
                                                    </span>
                                                @endif
                                            </li>
                                            <li>
                                                <a class="dropdown-item cursor-pointer text-primary" href="{{ route('admin.branches.edit', [$branch->id]) }}">
                                                    <i class="bx bx-edit-alt me-1"></i><span>ویرایش</span>
                                                </a>
                                            </li>
                                            <li>
                                                <span class="dropdown-item text-danger cursor-pointer" wire:click="deleteBranch({{ $branch->id }})">
                                                    <i class="bx bx-trash me-1"></i><span>حذف</span>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Branch Modal -->
                        <div class="modal fade" id="branchModal{{ $branch->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>نام شعبه:</strong></div>
                                            <div class="col-8">{{ $branch->name }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>نام مدیر:</strong></div>
                                            <div class="col-8">{{ $branch->manager }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>استان:</strong></div>
                                            <div class="col-8">{{ $branch->province?->name }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>آدرس:</strong></div>
                                            <div class="col-8">{{ $branch->address }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>تلفن:</strong></div>
                                            <div class="col-8">{{ $branch->phones()->pluck('number')->implode(',') }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>سایت:</strong></div>
                                            <div class="col-8">{{ $branch->site }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>شماره کارت:</strong></div>
                                            <div class="col-8">{{ $branch->bank_card_number }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>نام بانک:</strong></div>
                                            <div class="col-8">{{ $branch->bank_card_name }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>نام صاحب کارت:</strong></div>
                                            <div class="col-8">{{ $branch->bank_card_owner }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>حداقل پرداخت:</strong></div>
                                            <div class="col-8">{{ number_format($branch->minimum_pay) }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>لینک پرداخت آنلاین:</strong></div>
                                            <div class="col-8">{{ $branch->online_pay_link }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4"><strong>تاریخ ایجاد:</strong></div>
                                            <div class="col-8">{{ \Verta::instance($branch->created_at)->format('%B %d %Y') }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12 text-center">
                                                <a href="{{ route('admin.branches.edit', [$branch->id]) }}" class="btn btn-success">ویرایش</a>
                                                <button class="btn btn-primary" data-bs-dismiss="modal">بستن</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <span class="d-block mt-3">{{ $branches->links() }}</span>
    </div>
</div>
