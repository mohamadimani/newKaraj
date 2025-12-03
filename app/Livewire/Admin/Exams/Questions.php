<?php

namespace App\Livewire\Admin\Exams;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Questions extends Component
{
    public $exam;
    public $question;
    public $answer_1;
    public $answer_2;
    public $answer_3;
    public $answer_4;
    public $editId;
    public $question_edit;
    public $answer_1_edit;
    public $answer_2_edit;
    public $answer_3_edit;
    public $answer_4_edit;

    use WithPagination, LivewireAlert;
    public function render()
    {
        $questions = Question::where('exam_id', $this->exam->id)->orderBy('id', 'DESC')->get();
        return view('livewire.admin.exams.questions', compact('questions'));
    }

    public function mount($exam)
    {
        $this->exam = $exam;
    }

    public function store()
    {
        $this->validate([
            "question" => 'required|string',
            "answer_1" => 'required',
            "answer_2" => 'required',
            "answer_3" => 'required',
            "answer_4" => 'required',
        ]);
        DB::beginTransaction();
        $question = Question::create([
            'exam_id' => $this->exam->id,
            'question' => $this->question,
            'answer_1' => $this->answer_1,
            'answer_2' => $this->answer_2,
            'answer_3' => $this->answer_3,
            'answer_4' => $this->answer_4,
            'created_by' => user()->id,
        ]);
        if ($question) {
            $this->question = null;
            $this->answer_1 = null;
            $this->answer_2 = null;
            $this->answer_3 = null;
            $this->answer_4 = null;

            DB::commit();
            return $this->alert('success', __('public.messages.successfully_saved'));
        }
        DB::rollBack();
        return $this->alert('error', __('public.messages.error_in_saving'));
    }

    public function setEditInfo($questionId)
    {
        $question = Question::find($questionId);

        $this->editId = $questionId;
        $this->question_edit = $question->question;
        $this->answer_1_edit = $question->answer_1;
        $this->answer_2_edit = $question->answer_2;
        $this->answer_3_edit = $question->answer_3;
        $this->answer_4_edit = $question->answer_4;
    }

    public function update($questionId)
    {
        $this->validate([
            "question_edit" => 'required|string',
            "answer_1_edit" => 'required',
            "answer_2_edit" => 'required',
            "answer_3_edit" => 'required',
            "answer_4_edit" => 'required',
        ]);
        DB::beginTransaction();
        $question = Question::find($questionId)->update([
            'exam_id' => $this->exam->id,
            'question' => $this->question_edit,
            'answer_1' => $this->answer_1_edit,
            'answer_2' => $this->answer_2_edit,
            'answer_3' => $this->answer_3_edit,
            'answer_4' => $this->answer_4_edit,
            'created_by' => user()->id,
        ]);
        if ($question) {
            $this->question_edit = null;
            $this->answer_1_edit = null;
            $this->answer_2_edit = null;
            $this->answer_3_edit = null;
            $this->answer_4_edit = null;
            $this->editId = null;

            DB::commit();
            return $this->alert('success', __('public.messages.successfully_saved'));
        }
        DB::rollBack();
        return $this->alert('error', __('public.messages.error_in_saving'));
    }
}
