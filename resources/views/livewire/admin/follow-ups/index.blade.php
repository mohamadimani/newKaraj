@php
use App\Enums\Payment\StatusEnum;
use App\Models\User;
use App\Models\CourseRegister;
@endphp


<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            @if ($queryUserId)
            @php
            $user = User::find($queryUserId);
            @endphp
            <span class="font-20">{!! __('follow_ups.follow_ups_of_user', ['user' => '<b class="text-primary">' . $user->fullName . '</b>']) !!}</span>
            @else
            <span class="font-20 fw-bold heading-color">{{ __('follow_ups.page_title') }}</span>
            @endif
            @if ($backUrl)
            <div class="card-header-actions">
                <a href="{{ $backUrl }}" class="btn btn-label-primary mb-3">
                    <i class="tf-icons fa-solid fa-arrow-right-from-bracket"></i>
                    {{ __('public.back') }}
                </a>
            </div>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label" for="search">{{ __('public.search') }}</label>
                    <input type="text" class="form-control" wire:model.live="search" placeholder="{{ __('follow_ups.search_all') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="isDone">{{ __('public.status') }}</label>
                    <select id="isDone" class="form-select" wire:model.live="isDone">
                        <option value="">{{ __('public.all') }}</option>
                        <option value="1" {{ $isDone ? 'selected' : '' }}>{{ __('follow_ups.is_done') }}</option>
                        <option value="0" {{ !$isDone ? 'selected' : '' }}>{{ __('follow_ups.not_done') }}</option>
                    </select>
                </div>
                <div class="col-md-3" wire:ignore>
                    <label for="selectedSecretaryId">{{ __('clues.secretary') }}</label> :
                    <select class="form-control select2 " id="selectedSecretaryId" wire:model.live="selectedSecretaryId" onchange="myFunction()">
                        <option value="0">{{ __('public.all') }}</option>
                        @foreach($secretaries as $secretary)
                        <option value="{{ $secretary->id }}">{{ $secretary->user->fullName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4" wire:ignore>
                    <label class="form-label" for="created_at">{{ __('public.created_at') }}</label>
                    <div class="input-group">
                        <input type="text" id="created_at" class="form-control text-start dob-picker" placeholder="{{ __('follow_ups.remember_time_placeholder') }}" value="{{ $created_at }}"
                            wire:model.live="created_at">
                        <span id="clear-remember-time" class="input-group-text cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" wire:click="$set('created_at', '')"
                            title="{{ __('public.clear') }}">
                            <i class="bx bxs-eraser"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap mt-3">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('follow_ups.user') }}</th>
                            <th>{{ __('follow_ups.title') }}</th>
                            <th>{{ __('follow_ups.description') }}</th>
                            <!-- <th>{{ __('follow_ups.remember_time') }}</th> -->
                            <th>{{ __('follow_ups.is_done') }}</th>
                            <th>{{ __('public.created_by') }}</th>
                            <th>{{ __('public.created_at') }}</th>
                            <th>{{ __('public.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($followUps as $followUp)
                        <tr>
                            <td>{{ calcIterationNumber($followUps, $loop) }}</td>
                            <td>
                                <span class="badge bg-label-primary">
                                    {{ $followUp->user->fullName }}
                                </span>
                            </td>
                            <td>{{ $followUp->title }}</td>
                            <td>{{ $followUp->description }}</td>
                            <!-- <td>{{ georgianToJalali($followUp->remember_time, true) }}</td> -->
                            <td>
                                <span class="badge bg-label-{{ $followUp->is_done ? 'success' : 'danger' }} me-1">
                                    {{ $followUp->is_done ? __('public.yes') : __('public.no') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-warning">
                                    {{ $followUp->createdBy->fullName }}
                                </span>
                            </td>
                            <td>{{ georgianToJalali($followUp->created_at , true) }}</td>
                            <td>
                                @if(!$followUp->is_done)
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn rounded-pill btn-icon btn-label-primary" wire:click="markAsDone({{ $followUp->id }})">
                                        <span class="tf-icons bx bx-check" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('follow_ups.mark_as_done') }}"></span>
                                    </button>
                                </div>
                                @else
                                <span>---</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(count($followUps) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
                @endif
            </div>
            <div class="p-3">
                <span class="d-block mt-3">{{ $followUps->links() }}</span>
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
            document.addEventListener('DOMContentLoaded', function() {
                $('#clear-remember-time').click(function() {
                    $('.dob-picker').val('');
                });
            });

            function myFunction() {
                const value = $('select#selectedSecretaryId').val();
                @this.setSelectedSecretaryId(value)
            }
            myFunction()
        </script>
    </div>
