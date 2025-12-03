<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card p-3">
        @if (Session::has('success'))
            <div class="alert alert-success  mb-5 text-center">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger  mb-5 text-center">
                {{ Session::get('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger ">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li class="font-13">* {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-header ">
            <h5 class="card-title col-ms-12">
                <span class=" col-md-8">
                    {{ __('payment_methods.page_title') }}
                </span>
            </h5>
            <form>
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3 d-flex justify-content-end">
                        <div class="col-md-3">
                            <input type="text" wire:model="title" id="title" class="form-control" placeholder="{{ __('payment_methods.title') }}">
                        </div>
                        <div class="col-md-3 mx-1">
                            <input type="text" wire:model="slug" id="slug" class="form-control" placeholder="{{ __('payment_methods.slug') }}">
                        </div>
                        <div class="col-md-3 mx-1">
                            <input type="number" wire:model="sort" id="sort" class="form-control" placeholder="{{ __('payment_methods.sort') }}">
                        </div>
                        <div class="col-md-3 mx-1">
                            <input type="text" wire:model="description" id="description" class="form-control" placeholder="{{ __('payment_methods.description') }}">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 d-flex justify-content-end">
                        <div class="col-md-3 mb-3">
                            <span wire:click='store()' class="btn btn-success w-100 ">{{ __('payment_methods.create') }}</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <hr class="mb-5 mt-3">
        <h5 class="mb-3">{{ __('payment_methods.list') }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('public.row_number') }}</th>
                        <th>{{ __('familiarity_ways.title') }}</th>
                        <th>{{ __('payment_methods.slug') }}</th>
                        <th>{{ __('payment_methods.sort') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.created_at') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($paymentMethods as $key => $paymentMethod)
                        <tr>
                            <td>{{ calcIterationNumber($paymentMethods, $loop) }}</td>
                            <td>
                                @if ($editRowId and $editRowId == $paymentMethod->id)
                                    <input type="text" wire:model='edit_title'>
                                @else
                                    {{ $paymentMethod->title }}
                                @endif
                            </td>
                            <td>
                                @if ($editRowId and $editRowId == $paymentMethod->id)
                                    <input type="text" wire:model='edit_slug'>
                                @else
                                    {{ $paymentMethod->slug }}
                                @endif
                            </td>
                            <td>
                                @if ($editRowId and $editRowId == $paymentMethod->id)
                                    <input type="number" wire:model='edit_sort'>
                                    <a wire:click='updatePaymentMethod' class="btn text-white btn-sm btn-success">{{ __('public.save') }}</a>
                                @else
                                    {{ $paymentMethod->sort }}
                                @endif
                            </td>
                            <td>
                                @if ($paymentMethod->is_active)
                                    <button class="btn text-white btn-sm btn-success" wire:click='updateStatus({{ $paymentMethod->id }}, false)'>{{ __('public.active') }}</button>
                                @else
                                    <button class="btn text-white btn-sm btn-danger" wire:click='updateStatus({{ $paymentMethod->id }}, true)'>{{ __('public.inactive') }}</button>
                                @endif
                            </td>
                            <td> {{ \Verta::instance($paymentMethod->created_at)->format('%d %B %Y') }} </td>
                            <td>
                                <div class=" ">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item cursor-pointer" wire:click='setEditRowId({{ $paymentMethod->id }})'><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                    </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($paymentMethods) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $paymentMethods->links() }}</span>
        </div>
    </div>
</div>
