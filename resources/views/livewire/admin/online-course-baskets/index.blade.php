<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">لیست کاربران دارای سبد خرید</h5>
                </div>
                <div class="table-responsive text-nowrap p-2  ">
                    <div class="d-flex justify-content-between">
                        <input type="text" class="form-control w-25 mb-3" wire:model.live="search" placeholder="جستجو">
                    </div>
                    @include('admin.layouts.alerts')
                    @if ($onlineCourseBasketUsers->count() > 0)
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>موبایل</th>
                                    <th>سبد خرید</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($onlineCourseBasketUsers as $index => $onlineCourseBasketUser)
                                    <tr class="text-center">
                                        <td>{{ $onlineCourseBasketUsers->firstItem() + $index }}</td>
                                        <td>
                                            <a class="d-block" href="{{ route('online-course-baskets.show', $onlineCourseBasketUser->user->id) }}">{{ $onlineCourseBasketUser->user->fullName }}</a>
                                        </td>
                                        <td>
                                            <a class="d-block" href="{{ route('online-course-baskets.show', $onlineCourseBasketUser->user->id) }}">{{ $onlineCourseBasketUser->user->mobile }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('online-course-baskets.show', $onlineCourseBasketUser->user->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center alert alert-info">
                            <span class="fw-bold font-16">سبد خرید کاربران خالی است</span>
                        </div>
                    @endif
                </div>
                {{ $onlineCourseBasketUsers->links() }}
            </div>
        </div>
    </div>
</div>
