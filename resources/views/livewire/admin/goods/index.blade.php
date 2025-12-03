<div class="container-fluid flex-grow-1 container-p-y">
    <style>
        .goods-image {
            width: 70px;
            height: 70px;
            padding: 2px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 0 2px silver;
        }

        .break-all{
            white-space: break-spaces !important;
            line-height: 16px !important;
        }

        .w-250{
            min-width: 250px !important;
        }
    </style>
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">لیست اموال</span>
            <a href="{{ route('goods.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-2"></i> افزودن </a>
        </div>
        @include('admin.layouts.alerts')
        <div class="px-3 row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="search" class="form-label">جستجو</label> {{ requireSign() }}
                    <input type="text" class="form-control" id="search" wire:model.live="search" value="{{ old('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="branch_id" class="form-label">شعبه</label> {{ requireSign() }}
                    <select class="form-control" id="branch_id" wire:model.live="branch_id">
                        <option value="">انتخاب کنید</option>
                        @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id')==$branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="class_room_id" class="form-label">کلاس</label> {{ requireSign() }}
                    <select class="form-control" id="class_room_id" wire:model.live="class_room_id">
                        <option value="">انتخاب کنید</option>
                        @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" {{ old('class_room_id')==$classRoom->id ? 'selected' : '' }}>{{ $classRoom->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap px-3">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th class="p-2">#</th>
                        <th class="p-2">نام</th>
                        <th class="p-2">شعبه</th>
                        <th class="p-2">کلاس</th>
                        <th class="p-2">تصویر</th>
                        <th class="p-2">تعداد</th>
                        <th class="p-2">وضعیت سلامت</th>
                        <th class="p-2">توضیحات</th>
                        <th class="p-2">تاریخ ایجاد</th>
                        <th class="p-2">ثبت کننده</th>
                        <th class="p-2">عملیات</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if(count($goods) > 0)
                    @foreach ($goods as $key => $item)
                    <tr class="text-center">
                        <td class="p-2">{{ $key + 1 }}</td>
                        <td class="p-2">{{ $item->name }}</td>
                        <td class="p-2">{{ $item->branch->name }}</td>
                        <td class="p-2">{{ $item->classRoom->name }}</td>
                        <td class="p-2">
                            <a href="{{ GetImage('goods/' . $item->image) }}" target="_blank">
                                <img src="{{ GetImage('goods/' . $item->image) }}" class="goods-image">
                            </a>
                        </td>
                        <td class="p-2">{{ $item->count }}</td>
                        <td class="p-2">{{ $item->health_status == 'good' ? 'سالم' : 'معیوب' }}</td>
                        <td class="p-2"><div class="break-all w-250">{{ $item->description }}</div></td>
                        <td class="p-2">{{ georgianToJalali($item->created_at, false) }}</td>
                        <td class="p-2">{{ $item->createdBy?->full_name ?? '-' }}</td>
                        <td class="p-2">
                            <button type="button" class="w-100 btn btn-info btn-sm d-block mb-1" data-bs-toggle="modal" data-bs-target="#showReportsModal{{ $item->id }}">
                                <i class="fa-solid fa-list me-2"></i> لیست گزارش‌ها
                            </button>

                            <!-- Reports List Modal -->
                            <div class="modal fade" id="showReportsModal{{ $item->id }}" tabindex="-1" aria-labelledby="showReportsModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="showReportsModalLabel{{ $item->id }}">لیست گزارش‌های {{ $item->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>استاد</th>
                                                            <th>تعداد</th>
                                                            <th>وضعیت سلامت</th>
                                                            <th>توضیحات</th>
                                                            <th>تصویر</th>
                                                            <th>تاریخ ثبت</th>
                                                            <th>ثبت کننده</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($item->reports->sortByDesc('created_at') as $index => $report)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $report->teacher?->user?->full_name ?? '-' }}</td>
                                                            <td>{{ $report->count }}</td>
                                                            <td>
                                                                @if($report->health_status == 'good')
                                                                سالم
                                                                @elseif($report->health_status == 'damaged')
                                                                معیوب
                                                                @else
                                                                ناموجود
                                                                @endif
                                                            </td>
                                                            <td><div class="break-all  w-250">{{ $report->description ?? '-' }}</div></td>
                                                            <td>
                                                                @if($report->image)
                                                                <a href="{{ GetImage('goods/' . $report->image) }}" target="_blank">
                                                                    <img src="{{ GetImage('goods/' . $report->image) }}" class="goods-image">
                                                                </a>
                                                                @else
                                                                -
                                                                @endif
                                                            </td>
                                                            <td>{{ georgianToJalali($report->created_at, false) }}</td>
                                                            <td>{{ $report->createdBy?->full_name ?? '-' }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center">هیچ گزارشی ثبت نشده است</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class=" w-100 btn btn-warning btn-sm d-block" data-bs-toggle="modal" data-bs-target="#addReportModal{{ $item->id }}">
                                <i class="fa-solid fa-plus me-2"></i> افزودن گزارش جدید
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="addReportModal{{ $item->id }}" tabindex="-1" aria-labelledby="addReportModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addReportModalLabel{{ $item->id }}">افزودن گزارش </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('goods.reports.store', $item->id) }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>نام</label>
                                                            <input type="text" class="form-control" disabled value="{{ $item->name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>کد</label>
                                                            <input type="text" class="form-control" disabled value="{{ $item->code }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>تعداد</label>
                                                            <input type="number" class="form-control" name="count" value="{{ $item->count }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>وضعیت سلامت</label>
                                                            <select class="form-control" name="health_status" required>
                                                                <option value="good" {{ $item->health_status == 'good' ? 'selected' : '' }}>سالم</option>
                                                                <option value="damaged" {{ $item->health_status == 'damaged' ? 'selected' : '' }}>معیوب</option>
                                                                <option value="not_exist" {{ $item->health_status == 'not_exist' ? 'selected' : '' }}>ناموجود</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>استاد</label> {{ requireSign() }}
                                                            <select class="form-control" name="teacher_id" required>
                                                                <option value="">انتخاب کنید</option>
                                                                @foreach ($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}">{{ $teacher->user->full_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>بارگذاری تصویر</label> {{ requireSign() }}
                                                            <div class="d-flex justify-content-center mt-1">
                                                                <input type="file" class="form-control " id="image" name="image" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group mb-3">
                                                            <label>توضیحات</label>
                                                            <textarea class="form-control" rows="3" name="description" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                                    <button type="submit" class="btn btn-primary">ذخیره</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="11">
                            <div class="spinner-border" wire:loading>
                                <span class="sr-only">...</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="11" class="text-center">هیچ اموالی یافت نشد
                            <div class="spinner-border" wire:loading>
                                <span class="sr-only">...</span>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="p-3">
                <span class="d-block mt-3">{{ $goods->links() }}</span>
            </div>
        </div>
    </div>
</div>
