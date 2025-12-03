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
                    <h5 class="card-title">لیست دوره های حضوری موجود در سبد خرید</h5>
                </div>
                <div class="table-responsive text-nowrap p-2 mb-5">
                    @include('users.layouts.alerts')
                    @if ($courseBaskets->count() > 0)
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
                            @foreach ($courseBaskets as $courseBasket)

                            @php
                            $coursePrice = $onlineBranch->minimum_pay;

                            if ($courseBasket->is_full_pay) {
                            $coursePrice = $courseBasket->course->price;

                            $discountPercent = $courseBasket?->discount?->amount ?? 0;
                            if ($courseBasket->discount?->amount_type->value == 'fixed') {
                            $discountPercent = ($courseBasket->discount->amount / $coursePrice) * 100;
                            $discountPercent = round($discountPercent, 5, true);
                            }

                            $finalPercent = $discountPercent + $fullPayDiscount;
                            $coursePrice = $coursePrice - ($coursePrice * $finalPercent / 100);
                            }

                            $totalAmount+= $coursePrice;

                            @endphp
                            <tr class="text-center">
                                <td>{{ $courseBasket->course->title }}</td>
                                <td>
                                    @if ($courseBasket->is_full_pay)
                                    <del class="text-danger font-12">{{ number_format($courseBasket->course->price) }}</del>
                                    <br>
                                    {{ number_format($coursePrice) }}
                                    @else
                                    {{ number_format($coursePrice) }}
                                    @endif
                                </td>
                                <td>
                                    <span type="submit" wire:click="destroy({{ $courseBasket->id }})" class="btn btn-danger btn-sm"><i class="bx bx-trash me-1"></i></span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="text-center font-16">
                                <td class="text-center">جمع کل</td>
                                <td>{{ number_format($totalAmount) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                    <div class="text-center alert alert-info">
                        <span class="fw-bold font-16">سبد خرید شما خالی است</span>
                    </div>
                    @endif
                    <div class="mt-4 text-center">
                        @if (auth()->user()->first_name && auth()->user()->last_name && auth()->user()->clue->familiarity_way_id)
                        <a href="{{ route('user.course-baskets.checkout') }}" class="btn btn-success col-md-6">ثبت سفارش</a>
                        @else
                        <span class="cursor-pointer btn btn-success col-md-6" data-bs-toggle="modal" data-bs-target="#studentModal{{ auth()->user()->id }}">
                            تکمیل اطلاعات وثبت سفارش
                        </span>
                        @endif
                        <a href="{{ route('user.courses.index') }}" class="btn btn-info col-md-6">افزودن دوره</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
</div>