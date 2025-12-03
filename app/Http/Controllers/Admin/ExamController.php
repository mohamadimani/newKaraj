<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Branch;
use App\Models\Exam;
use App\Models\ExamProfession;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        return view('admin.exams.index');
    }

    public function create()
    {
        $professions = resolve(ProfessionRepository::class)->getListQuery(Auth::user());
        $professions = $professions->whereNotIn('id', function ($query) {
            $query->select('profession_id')
                ->from('exam_profession');
        });
        $professions = $professions->active()->get();
        $branches = Branch::active()->get();
        return view('admin.exams.create', compact('professions', 'branches'));
    }

    public function store(StoreExamRequest $request)
    {
        $exam = Exam::create([
            'title' => $request->title,
            'description' => $request->description ?? null,
            'duration_min' => $request->duration_min,
            'branch_id' => $request->branch_id,
            'passing_score' => $request->passing_score,
            'question_count' => $request->question_count,
            'created_by' => user()->id,
        ]);
        if ($exam) {
            $exam->professions()->sync($request->profession_id);
        }
        return redirect()->route('exams.index')->with('success', __('public.messages.successfully_saved'));
    }

    public function edit(Exam $exam)
    {
        $professions = resolve(ProfessionRepository::class)->getListQuery(Auth::user());
        $professions = $professions->whereNotIn('id', function ($query) {
            $query->select('profession_id')
                ->from('exam_profession');
        });
        $professions = $professions->active()->get();
        $branches = Branch::active()->get();
        return view('admin.exams.edit', compact('exam', 'professions', 'branches'));
    }

    public function update(Exam $exam, UpdateExamRequest $request)
    {
        $exam->update($request->validated());
        $exam->professions()->sync($request->profession_id);
        return redirect()->route('exams.index')->with('success', __('public.messages.successfully_updated'));
    }

    public function question(Exam $exam)
    {
        return view('admin.exams.questions',compact('exam'));
    }
}
