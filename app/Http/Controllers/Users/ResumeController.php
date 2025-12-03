<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function index()
    {
        return view('users.resume.index');
    }
    public function upload_image(Request $request)
    {
        $user = User::find(user()->id);
        if ($request->personal_image) {
            if ($user->student->personal_image) {
                DeleteImage('students/personal/' . $user->student->personal_image);
            }
            $user->student->personal_image = SaveImage($request->personal_image, 'students/personal');
            $user->student->save();
            return redirect()->back()->with('success', __('students.messages.successfully_updated'));
        }
        return redirect()->back()->with('error', __('students.messages.error_occurred'));
    }
    public function template()
    {
        return view('users.resume.index');
    }
}
