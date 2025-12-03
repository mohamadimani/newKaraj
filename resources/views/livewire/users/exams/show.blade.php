<div>
    <div class="container-fluid flex-grow-1 container-p-y  w-100">
        @include('users.layouts.alerts')
        <div class="card pb-3">
            <div class="align-items-center card-header d-flex justify-content-between">
                <span class="font-20 fw-bold heading-color">سوالات : <span class="text-info">{{ $exam->title }}</span></span>
                {{-- {{ $time ?? '20:00' }} --}}
            </div>
            <div class="card-body font-15">
                <hr>
                <div class="col-md-12 text-center">
                    <div class="mt-2">
                        <span> امتیاز آزمون : </span>
                        <span>{{ $exam->passing_score }}</span>
                    </div>
                    <div class="mt-2">
                        <span>تعداد سوال : </span>
                        <span>{{ $exam->question_count }}</span>
                    </div>
                    <hr>
                    <div class="mt-2">
                        <span>تعداد پاسخ : </span>
                        <span>{{ $answerCount }}</span>
                    </div>
                </div>
                <hr>
                @if ($question and $answerCount < $exam->question_count)
                    <div class="col-md-12">
                        <p>{{ $question->question }}</p>
                    </div>
                    @php
                        $answers = [
                            ['id' => 'answer_1', 'text' => $question->answer_1, 'value' => 1],
                            ['id' => 'answer_2', 'text' => $question->answer_2, 'value' => 2],
                            ['id' => 'answer_3', 'text' => $question->answer_3, 'value' => 3],
                            ['id' => 'answer_4', 'text' => $question->answer_4, 'value' => 4],
                        ];
                        shuffle($answers);
                        $answers = collect($answers)->map(function ($a) {
                            return (object) [
                                'id' => $a['id'],
                                'text' => $a['text'],
                                'value' => (int) $a['value'],
                            ];
                        });
                    @endphp
                    @foreach ($answers as $answer)
                        <div class="form-check form-check-success">
                            <input wire:model="answer" name="answer" class="form-check-input" type="radio" value="{{ $answer->value }}" id="{{ $answer->id }}">
                            <label class="form-check-label" for="{{ $answer->id }}">{{ $answer->text }}</label>
                        </div>
                    @endforeach
                    <div class="pt-4 text-end">
                        @if ($answerCount == $exam->question_count - 1)
                            <button wire:click='saveAnswers({{ $exam->id }},{{ $question->id }},true)' class="btn btn-success">اتمام آزمون</button>
                        @else
                            <button wire:click='saveAnswers({{ $exam->id }},{{ $question->id }})' class="btn btn-primary">سوال بعدی</button>
                        @endif
                        <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('user.exams.index') }}">{{ __('public.cancel') }}</a>
                    </div>
                @elseif ($answerCount == $exam->question_count)
                    <div class="col-md-12 text-center">
                        <div class="mt-3">
                            <span>تعداد پاسخ صحیح: </span>
                            <span class="text-success">{{ $correctAnswers }}</span>
                        </div>
                        <div class="mt-3">
                            <span>تعداد پاسخ غلط: </span>
                            <span class="text-danger mt-3">{{ $wrongAnswers }}</span>
                        </div>
                        <div class="mt-3">
                            <span>امتیاز کسب شده : </span>
                            <span class="text-danger mt-3">{{ $score }}</span>
                        </div>
                        {{-- <div class="mt-2">
                            <span>نتیجه آزمون: </span>
                            @if ($score > ($this->exam->passing_score / 100) * 80)
                                <span class="badge bg-label-success mt-3">قبول شده</span>
                            @else
                                <span class="badge bg-label-danger mt-3">رد شده</span>
                            @endif
                        </div> --}}
                    </div>
                @else
                    <div class="text-center py-5">
                        هیچ سوالی وجود ندارد!
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
