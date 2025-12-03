<div class="container-fluid flex-grow-1 container-p-y">
    @php
        use App\Enums\Payment\StatusEnum;
        use App\Models\User;
        use App\Models\CourseRegister;
        use App\Models\CourseReserve;
        use App\Models\Order;
        use App\Constants\PermissionTitle;
        use App\Models\PaymentMethod;
        use App\Models\Technical;
    @endphp

    <style>
        /* payment image style */
        .icon-container {
            display: inline-block;
            cursor: pointer;
            position: relative;
        }

        .imageOverlay {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            height: auto;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            overflow: auto;
        }

        .imageOverlay img {
            width: 255px;
            height: auto;
            display: block;
            margin: 10px 1% 0 0;
            opacity: 1;
            /* padding: 3px; */
            border: 4px solid white;
            border-radius: 5px;
            box-shadow: 1px 1px 5px silver;
        }

        #zoomImage:hover {
            width: 380px;
            height: auto;
            transition: transform 0.2s, transform-origin 0.2s;
        }

        .closeImage {
            position: absolute;
            top: 2px;
            right: 0;
            font-size: 15px;
        }
    </style>
    <div class="card pb-3">
        <div class="align-items-center card-header  justify-content-between">
            <span class="font-20 fw-bold heading-color">عودت وجه</span>
        </div>
        <div class="card-body row">
            @include('admin.layouts.alerts')
        </div>
        <div class="card-body border-bottom">
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label" for="search">{{ __('public.search') }}</label> :
                    <input type="text" class="form-control" wire:model.live="search" placeholder="{{ __('payments.search_all') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="startDate">از</label> :
                    <input data-jdp type="text" class="form-control " wire:model.live="startDate" placeholder="تاریخ">
                    @include('admin.layouts.jdp', ['time' => false])
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="endDate">تا</label> :
                    <input data-jdp type="text" class="form-control " wire:model.live="endDate" placeholder="تاریخ">
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>کارآموز</th>
                        <th>{{ __('payments.course') }}</th>
                        <th>مبلغ عودت <sub>(تومان)</sub></th>
                        <th>{{ __('payments.description') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>ثبت کننده</th>
                        <th>تاریخ ثبت</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($refunds as $refund)
                        <tr class="text-center">
                            <td>
                                {{ calcIterationNumber($refunds, $loop) }}
                            </td>
                            <td>{{ $refund->user->fullName }}</td>
                            <td><span style="line-height: 20px;">{!! $refund->course->title !!}</span></td>
                            <td> {{ number_format($refund->amount) }} </td>
                            <td>{{ $refund->description }}</td>
                            <td>
                                @if ($refund->confirmed_by)
                                    <span class="badge bg-label-success">تایید شده</span>
                                @else
                                    <span class="badge bg-label-danger">درانتظار</span>
                                @endif
                            </td>
                            <td>{{ georgianToJalali($refund->created_at, true) }}</td>
                            <td> <span class="badge bg-label-warning"> {{ $refund->createdBy->fullName }} </span></td>
                            <td>
                                @if (Auth::user()->hasPermissionTo(PermissionTitle::REFUND_CONFIRM) and !$refund->confirmed_by)
                                    <button type="button" class="btn rounded-pill btn-icon btn-label-primary verify-payment-button" wire:click="confirmRefund({{ $refund->id }})">
                                        <span class="tf-icons bx bx-check" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('payments.verify') }}"></span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($refunds) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $refunds->links() }}</span>
        </div>
    </div>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.625rem 0.5rem;
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }
    </style>

    <script>
        const editPaidAmount = document.getElementById('editPaidAmount');
        const editPersianTextElement = document.getElementById('edit-persian-number');
        editPaidAmount.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            if (this.value) {
                this.value = new Intl.NumberFormat().format(this.value);
                const numericValue = parseInt(this.value.replace(/,/g, ''));
                const persianText = numberToPersianText(numericValue);
                editPersianTextElement.textContent = persianText + ' تومان';
            } else {
                editPersianTextElement.textContent = '';
            }
        });

        function myFunction() {
            const value = $('select#selectedSecretaryId').val();
            @this.setSelectedSecretaryId(value)
        }
        myFunction()

        function showImageOverlay(id) {
            $('.imageOverlay').hide()
            $('#imageOverlay' + id).show()
        }

        function hideImageOverlay() {
            $('.imageOverlay').hide()
        }
    </script>
</div>
