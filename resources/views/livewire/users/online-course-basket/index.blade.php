<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <style>
            td,
            th {
                padding: 4px !important;
            }
        </style>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">سبد خرید دوره های آنلاین</h5>
                </div>
                <div class="card-body" wire:ignore>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-16">افزودن دوره آنلاین به سبد خرید</label>
                            <select id="onlineCourseId" wire:model="onlineCourseId" class="form-select select2" onchange="myFunction()">
                                <option value="">انتخاب کنید</option>
                                @foreach ($onlineCourses as $onlineCourse)
                                <option value="{{ $onlineCourse->id }}">{{ $onlineCourse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3 ">
                            <label class="form-label font-16"> &nbsp; </label>
                            <button type="submit" wire:click="store" class="btn btn-primary d-block w-100">افزودن </button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-header">
                    <h6 class="card-title">لیست دوره های موجود در سبد خرید</h6>
                </div>
                <div class="table-responsive text-nowrap p-2 mb-5">
                    @include('users.layouts.alerts')
                    @if ($onlineCourseBaskets->count() > 0)
                    <table class="table  table-striped table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>نام دوره</th>
                                <th>شهریه <sub>(تومان)</sub></th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                            $totalAmount = 0;
                            @endphp
                            @foreach ($onlineCourseBaskets as $onlineCourseBasket)
                            <tr class="text-center">
                                <td>{{ $onlineCourseBasket->onlineCourse->name }}</td>
                                @if (
                                $onlineCourseBasket->onlineCourse->discount_amount > 0 &&
                                intval($onlineCourseBasket->onlineCourse->discount_start_at) <= time() && intval($onlineCourseBasket->onlineCourse->discount_expire_at) >= time())
                                    <td>{{ number_format($onlineCourseBasket->onlineCourse->discount_amount) }}</td>
                                    @php
                                    $totalAmount += $onlineCourseBasket->onlineCourse->discount_amount;
                                    @endphp
                                    @else
                                    <td>{{ number_format($onlineCourseBasket->onlineCourse->amount) }}</td>
                                    @php
                                    $totalAmount += $onlineCourseBasket->onlineCourse->amount;
                                    @endphp
                                    @endif
                                    <td>
                                        <span type="submit" wire:click="destroy({{ $onlineCourseBasket->id }})" class="btn btn-danger btn-sm"><i class="bx bx-trash me-1"></i></span>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="text-center font-16">
                                <td class="text-center">جمع کل</td>
                                <td>{{ number_format($totalAmount) }}</td>
                                <td>
                                    @if (auth()->user()->first_name && auth()->user()->last_name && auth()->user()->clue->familiarity_way_id)
                                    <a href="{{ route('user.online-course-baskets.checkout') }}" class="btn btn-success w-100">ثبت سفارش</a>
                                    @else
                                    <span class="cursor-pointer btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#studentModal{{ auth()->user()->id }}">
                                        تکمیل اطلاعات وثبت سفارش
                                    </span>
                                    <!-- Student Modal -->
                                    <div class="modal fade" id="studentModal{{ auth()->user()->id }}" tabindex="-1" aria-hidden="true" wire:ignore.self>
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id=" ">تکمیل اطلاعات وثبت سفارش
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6 mb-5">
                                                            <label for="first_name" class="form-label font-14">نام</label>{{ requireSign() }}
                                                            <input type="text" class="form-control" id="first_name" name="first_name" required wire:model="first_name">
                                                            @error('first_name')
                                                            <span class="text-danger font-11">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6 mb-5">
                                                            <label for="last_name" class="form-label font-14">نام خانوادگی</label>{{ requireSign() }}
                                                            <input type="text" class="form-control" id="last_name" name="last_name" required wire:model="last_name">
                                                            @error('last_name')
                                                            <span class="text-danger font-11">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-12 mb-5">
                                                            <label for="familiarity_way_id" class="form-label font-14">روش آشنایی</label>{{ requireSign() }}
                                                            <select name="familiarity_way_id" id="familiarity_way_id" class="form-select" wire:model="familiarity_way_id">
                                                                <option value="">انتخاب کنید</option>
                                                                @foreach ($familiarityWays as $familiarityWay)
                                                                <option value="{{ $familiarityWay->id }}">{{ $familiarityWay->title }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('familiarity_way_id')
                                                            <span class="text-danger font-11">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-12 mb-5">
                                                            <span class="btn btn-primary font-14 w-100 " wire:click="updateClueInfo">ثبت اطلاعات</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Student Modal -->
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                    <div class="text-center alert alert-info">
                        <span class="fw-bold font-16">سبد خرید شما خالی است</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        function myFunction() {
                            const value = $('select#onlineCourseId').val();
                            @this.setOnlineCourseId(value)
        }
        myFunction()
    </script>
</div>
