<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <style>
                .card-body {
                    padding: 10px !important;
                }

                .container-xxl {
                    padding: 10px !important;
                }

                @media (max-width: 768px) {
                    .banner {
                        height: 60px !important;
                    }
                }

                @media (min-width: 768px) {
                    .banner {
                        height: 130px !important;
                    }
                }

                .banner {
                    width: 100%;
                    overflow: hidden;
                    padding: 10px !important;
                    border-radius: 5px;
                    background-color: #ffffff;
                    margin: 10px 0px;
                    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
                }

                .banner img {
                    width: 100%;
                    height: 100%;
                    cursor: pointer;
                }
            </style>
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3   text-gray-800">دوره های آنلاین</h1>
            </div>
            <div class=" justify-content-end mb-3">
                @if ($discount)
                <div class="banner">
                    <img src="{{ asset('public/images/discounts/banners/' . $discount->banner) }}" class="rounded discount_banner" onclick="copy('{{ $discount->code }}')">
                </div>
                <div class="d-grid w-100 mt-3 pt-2">
                    <span class="text-center d-none alert alert-success copied_discount_code"><strong>کپی شد</strong></span>
                    <script>
                        function copy(code) {
                            navigator.clipboard.writeText(code);
                            $('span.copied_discount_code').removeClass('d-none');
                            $('span.copied_discount_code').fadeIn(100);
                            setTimeout(() => {
                                $('span.copied_discount_code').fadeOut(300);
                            }, 1500);
                        }
                    </script>
                </div>
                @endIf
            </div>
            <div class="d-flex justify-content-end mb-3">
                <input type="text" wire:model.live="search" class="form-control" placeholder="جستجوی دوره">
            </div>

            @include('admin.layouts.alerts')
            <div class="table-responsive">
                <style>
                    table td,
                    table th {
                        padding: 6px 4px !important;
                    }
                </style>
                <table class="table-bordered" id="dataTable" width="100%">
                    <thead>
                        <tr class="text-center ">
                            <th>ردیف</th>
                            <th>نام</th>
                            <th>مبلغ(تومان)</th>
                            <th>طول دوره(ساعت)</th>
                            <th>استاد</th>
                            <th>گزینه ها</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($onlineCourses as $index => $course)
                        <tr class="text-center">
                            <td>{{ $onlineCourses->firstItem() + $index }}</td>
                            <td>{{ $course->name }}</td>
                            <td class="text-center">
                                @if ($course->discount_amount > 0 and intval($course->discount_start_at) <= time() and intval($course->discount_expire_at) >= time())
                                    <del>{{ number_format($course->amount) }}</del>
                                    <span class="text-success">{{ number_format($course->discount_amount) }}</span>
                                    @else
                                    <span>{{ number_format($course->amount) }}</span>
                                    @endif
                            </td>
                            <td class="text-center">{{ $course->duration_hour }}</td>
                            <td class="text-center">{{ $course->teacher?->user->full_name }}</td>
                            <td class="text-center">
                                <a href="{{ route('user.online-courses.show', $course) }}" class="btn btn-sm btn-primary">
                                    مشاهده دوره</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">دوره آنلاین یافت نشد!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <span class="d-block mt-3">{{ $onlineCourses->links() }}</span>
        </div>
    </div>
</div>
