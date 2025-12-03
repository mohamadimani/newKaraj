<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header heading-color">افزودن سوالات برای : <span class="text-info">{{ $exam->title }}</span></h5>
        @include('admin.layouts.alerts')
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-sm-6 mb-3">
                    <label class="form-label" for="question">سوال</label>
                    <input wire:model="question" value="{{ old('question') }}" type="text" id="question" class="form-control text-start" placeholder="سوال">
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="answer_1">پاسخ 1 : (<span class="text-danger">پاسخ صحیح</span>)</label>
                    <input wire:model="answer_1" value="{{ old('answer_1') }}" type="text" id="answer_1" class="form-control text-start" placeholder="پاسخ">
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="answer_2">پاسخ 2</label> :
                    <input wire:model="answer_2" value="{{ old('answer_2') }}" type="text" id="answer_2" class="form-control text-start" placeholder="پاسخ">
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="answer_3">پاسخ 3</label> :
                    <input wire:model="answer_3" value="{{ old('answer_3') }}" type="text" id="answer_3" class="form-control text-start" placeholder="پاسخ">
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="answer_4">پاسخ 4</label> :
                    <input wire:model="answer_4" value="{{ old('answer_4') }}" type="text" id="answer_4" class="form-control text-start" placeholder="پاسخ">
                </div>
            </div>
            <div class="pt-4 text-end">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('exams.index') }}">{{ __('public.cancel') }}</a>
                <span class="btn btn-primary" wire:click="store()">ثبت</span>
            </div>
            <hr class="mt-4 mb-4">
            <div class="  table-responsive ">
                <h5>لیست سوالات : </h5>
                <table class="table text-center  table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>سوال </th>
                            <th>پاسخ 1<sub class="text-danger"> (صحیح)</sub></th>
                            <th>پاسخ 2</th>
                            <th>پاسخ 3</th>
                            <th>پاسخ 4</th>
                            <th>{{ __('public.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($questions as $index => $question)
                        <tr class="text-center">
                            <td class="text-center">{{ 1 + $index }}</td>
                            @if ($editId>0 and $editId == $question->id)
                            <td><input wire:model="question_edit" value="{{ old('question_edit') }}" type="text" class="form-control"></td>
                            <td><input wire:model="answer_1_edit" value="{{ old('answer1_edit') }}" type="text" class="form-control"></td>
                            <td><input wire:model="answer_2_edit" value="{{ old('answer2_edit') }}" type="text" class="form-control"></td>
                            <td><input wire:model="answer_3_edit" value="{{ old('answer3_edit') }}" type="text" class="form-control"></td>
                            <td><input wire:model="answer_4_edit" value="{{ old('answer4_edit') }}" type="text" class="form-control"></td>
                            <td><span class="btn btn-success btn-sm" wire:click="update({{ $question->id }})"> ثبت</span></td>
                            @else
                            <td><span>{{ $question->question }}</span></td>
                            <td><span>{{ $question->answer_1 }}</span></td>
                            <td><span>{{ $question->answer_2 }}</span></td>
                            <td><span>{{ $question->answer_3 }}</span></td>
                            <td><span>{{ $question->answer_4 }}</span></td>
                            <td><span class="btn btn-info btn-sm" wire:click="setEditInfo({{ $question->id }})"> ویرایش</span></td>

                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
