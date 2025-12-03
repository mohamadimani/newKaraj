<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CourseRegister\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentUpdateRequest;
use App\Models\Course;
use App\Models\CourseRegister;
use App\Models\FamiliarityWay;
use App\Models\PaymentMethod;
use App\Models\Profession;
use App\Models\Province;
use App\Models\Student;
use App\Models\Technical;
use App\Repositories\Profession\ProfessionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StudentController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Student::class);
        return view('admin.students.index');
    }

    public function edit(Student $student)
    {
        Gate::authorize('edit', $student);
        $provinces = Province::all();

        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        $familiarityWays = FamiliarityWay::all();
        $courses = Course::all();
        $paymentMethods = PaymentMethod::all();

        return view('admin.students.edit', compact('student', 'provinces', 'professions', 'familiarityWays', 'courses', 'paymentMethods'));
    }

    public function update(Student $student, StudentUpdateRequest $request)
    {
        Gate::authorize('update', $student);
        try {
            DB::beginTransaction();

            $nationalCode = null;
            if ($nationalCode = $request->validated()['national_code'] and !checkNationalCode($nationalCode)) {
                return redirect()->back()->with('error', 'کد ملی صحیح نیست');
            }

            $student->update([
                'father_name' => $request->validated()['father_name'] ?? null,
                'national_code' => $nationalCode,
                'military_status' => $request->validated()['military_status'] ?? null,
                'education' => $request->validated()['education'] ?? null,
                'birth_place' => $request->validated()['birth_place'] ?? null,
            ]);

            $student->user()->update([
                'first_name' => $request->validated()['first_name'] ?? null,
                'last_name' => $request->validated()['last_name'] ?? null,
                'mobile' => $request->validated()['mobile'] ?? null,
                'mobile2' => $request->validated()['mobile2'] ?? null,
                'phone' => $request->validated()['phone'] ?? null,
                'gender' => $request->validated()['gender'] ?? null,
                'birth_date' => $request->validated()['birth_date'] ? toGeorgianDate($request->validated()['birth_date']) : null,
                'province_id' => $request->validated()['province_id'] ?? null,
            ]);

            $student->user->clue()->update([
                'familiarity_way_id' => $request->validated()['familiarity_way_id'] ?? null,
            ]);

            $student->user->clue->professions()->sync($request->validated()['profession_ids']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('students.edit', $student->id)->with('success', __('students.messages.successfully_updated'));
    }

    public function uploadImages(Student $student, Request $request)
    {
        Gate::authorize('uploadStudentDocuments', $student);
        $request->validate([
            'personal_image' => ['image', 'max:4400', 'mimes:gif,png,jpg,jpeg,webp,svg'],
            'id_card_image' => ['image', 'max:4400', 'mimes:gif,png,jpg,jpeg,webp,svg'],
            'birth_certificate_image' => ['image', 'max:4400', 'mimes:gif,png,jpg,jpeg,webp,svg'],
        ]);

        DB::beginTransaction();
        try {
            if ($request->personal_image) {
                if ($student->personal_image) {
                    DeleteImage('students/personal/' . $student->personal_image);
                }
                $student->personal_image = SaveImage($request->personal_image, 'students/personal');
            }
            if ($request->id_card_image) {
                if ($student->id_card_image) {
                    DeleteImage('students/id-card/' . $student->id_card_image);
                }
                $student->id_card_image = SaveImage($request->id_card_image, 'students/id-card');
            }
            if ($request->birth_certificate_image) {
                if ($student->birth_certificate_image) {
                    DeleteImage('students/birth-certificate/' . $student->birth_certificate_image);
                }
                $student->birth_certificate_image = SaveImage($request->birth_certificate_image, 'students/birth-certificate');
            }
            $student->save();
            DB::commit();
            return redirect()->route('students.edit', $student->id)->with('success', __('students.messages.successfully_updated'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
