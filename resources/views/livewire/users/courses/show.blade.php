<div>
    @php
        $fullPayAmount = $course->price - ($course->price * $fullPayDiscount) / 100;
    @endphp
    <div class="mt-4">
        <div class="alert alert-info">
            <div class="d-flex align-items-center">
                <div>
                    <h5 class="mb-1">نحوه پرداخت را انتخاب کنید</h5>
                    <div class="d-flex align-items-center">
                        <div class="mt-3">
                            <div class="form-check mb-2 @if ($payment_type == 'prepayment') alert alert-warning @endif ">
                                <input class="form-check-input" type="radio" wire:model.live='payment_type' id="prepayment" value="prepayment">
                                <label class="form-check-label" for="prepayment">
                                    پیش پرداخت {{ number_format($onlineBranch->minimum_pay) }} تومان  <span> (اقساطی) </span>
                                </label>
                            </div>
                            <div class="form-check @if($payment_type == 'full_pay') alert alert-warning @endif">
                                <input class="form-check-input" type="radio" wire:model.live="payment_type" id="full_pay" value="full_pay">
                                <label class="form-check-label" for="full_pay">پرداخت کامل با تخفیف 5%</label>
                                <h5 class="mb-0 d-inline">
                                    <del style="color: red">{{ number_format($course->price) }}</del>
                                    <span class="ms-2">{{ number_format($fullPayAmount) }}</span>
                                </h5>
                                <small class="ms-2">تومان</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info mt-3">
        <div class="d-flex align-items-center">
            <div>
                @if (session()->get('discount_id') and ($course_id = session()->get('course_id')) and $course_id == $course->id)
                    <h5 class="mb-1">قیمت دوره با تخفیف به شرط پرداخت کامل</h5>
                    <div>
                        <div>
                            <span>کد تخفیف</span>
                            <span>{{ session('discount_code') }}</span>
                            <span>اعمال شد</span>
                        </div>
                        <h5 class="mb-0 d-inline">
                            <del style="color: red">{{ number_format($fullPayAmount) }}</del>
                            <span class="ms-2">{{ number_format($fullPayAmount - session()->get('coursePriceWithDiscount')) }}</span>
                        </h5>
                        <small class="ms-2">تومان</small>
                    </div>
                @else
                    <h5 class="mb-1">کد تخفیف</h5>
                    <div class="input-group">
                        <input type="text" wire:model="discount_code" class="form-control" placeholder="کد تخفیف خود را وارد کنید">
                        <button class="btn btn-outline-secondary" type="button" wire:click="applyDiscountCode">اعمال</button>
                    </div>
                    <div class="mt-2">
                        @error('discount_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary w-100" wire:click='addToBasket({{ $course }})'>
                <i class="fa fa-shopping-basket me-2"></i>
                افزودن به سبد خرید
            </button>
        </div>
        <div class="col-md-6">
            <a href="{{ route('user.course-baskets.index') }}" class="btn btn-info w-100">
                <i class=' tf-icons fa-solid fa-cart-shopping me-2'></i>
                <div>مشاهده سبد خرید</div>
            </a>
        </div>
    </div>
</div>
