<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3   text-gray-800">دوره های آنلاین</h1>
                <a href="{{ route('online-courses.sms_marketing') }}" class="btn btn-warning">
                    <i class="fas fa-plus"></i> افزودن پیامک تبلیغاتی
                </a>
                <a href="{{ route('online-courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> افزودن دوره جدید
                </a>
            </div>
            @include('admin.layouts.alerts')
            <div class="table-responsive">

                <div class="d-flex justify-content-between">
                    <input type="text" class="form-control w-25 mb-3" wire:model.live="search" placeholder="جستجو">
                </div>
                <style>
                    table td, table th {
                        padding: 6px 4px!important;
                    }
                </style>
                <table class="table-bordered" id="dataTable" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th>ردیف</th>
                            <th>نام</th>
                            <th>کلید اسپات پلیر</th>
                            <th>مبلغ</th>
                            <th>طول دوره</th>
                            <th>مبلغ تخفیف خورده</th>
                            <th>از تاریخ</th>
                            <th>تا تاریخ</th>
                            <th>تعداد ثبت نام</th>
                            <th>گزینه ها</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($onlineCourses as $index => $course)
                            <tr class="text-center">
                                <td>{{ $onlineCourses->firstItem() + $index }}</td>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->spot_key }}</td>
                                <td>{{ $course->amount }}</td>
                                <td>{{ $course->duration_hour }}</td>
                                <td>{{ $course->discount_amount }}</td>
                                <td>{{ $course->discount_start_at_jalali }}</td>
                                <td>{{ $course->discount_expire_at_jalali }}</td>
                                <td>{{ $course->registered_count ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('online-courses.edit', $course) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('online-courses.destroy', $course) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center alert alert-warning">دوره آنلاین یافت نشد!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <span class="d-block mt-3">{{ $onlineCourses->links() }}</span>
        </div>
    </div>
</div>
