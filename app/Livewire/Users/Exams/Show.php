<?php

namespace App\Livewire\Users\Exams;

use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\UserExamNumber;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    public $exam;

    public $courseRegister;
    public $question;
    public $answer;

    public $answerCount = 0;
    public $correctAnswers = 0;
    public $wrongAnswers = 0;
    public $score = 0;
    public $finishExam = false;

    use WithPagination, LivewireAlert;
    public function render()
    {
        $userAnswer = UserAnswer::where('user_id', user()->id)->where('exam_id', $this->exam->id);
        $userAnswerQuestionsId = clone $userAnswer;
        $userAnswerQuestionsId =  $userAnswerQuestionsId->pluck('question_id')->toArray();

        $this->answerCount = count($userAnswerQuestionsId);

        if ($this->answerCount == $this->exam->question_count) {
            $this->correctAnswers = $userAnswer->where('is_correct', true)->count();
            $this->wrongAnswers =  $this->answerCount - $this->correctAnswers;
            $this->score = ceil($this->exam->passing_score  / $this->exam->question_count * $this->correctAnswers);
            if ($this->finishExam) {
                $userExamNumber = UserExamNumber::create([
                    'user_id' => user()->id,
                    'student_id' => user()->student->id,
                    'course_register_id' => $this->courseRegister->id,
                    'course_id' => $this->courseRegister->course_id,
                    'profession_id' => $this->courseRegister->course->profession_id,
                    'exam_type' => 'written',
                    'exam_number' => $this->score,
                    'description' => 'آزمون آنلاین کتبی از پرتال',
                    'created_by' => FIDAR_AI(),
                ]);
                $this->finishExam = false;
            }
        }

        $this->question = $question = Question::where('exam_id', $this->exam->id)->whereNotIn('id', $userAnswerQuestionsId)->inRandomOrder()->first();
        return view('livewire.users.exams.show', compact('question'));
    }

    public function saveAnswers($examId, $questionId, $finishExam = false)
    {
        $userAnswer = UserAnswer::create([
            'user_id' => user()->id,
            'exam_id' => $examId,
            'question_id' => $questionId,
            'answer' => $this->answer,
            'is_correct' => $this->answer == 1 ? true : false,
        ]);
        if ($userAnswer) {
            if ($finishExam) {
                $this->finishExam = true;
            }

            $this->answer = null;
            return $this->alert('success', __('public.messages.successfully_saved'));
        }
        return $this->alert('error', __('public.messages.error_in_saving'));
    }
}
