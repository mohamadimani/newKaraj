<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if ($online_course_id = session('online_course_id') and $online_course_id > 0) {
            session()->forget('online_course_id');
            return redirect()->route('user.online-courses.show', [$online_course_id]);
        }
        if ($course_id = session('course_id')  and $course_id > 0) {
            session()->forget('course_id');
            return redirect()->route('user.courses.show', [$course_id]);
        }
        if (session('license') > 0) {
            session()->forget('license');
            return redirect()->route('user.documents.course-license');
        }
        return view('users.profile.index');
    }

    public function updateNames(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        return response()->json(['success' => true]);
    }

    public function wallet(Request $request)
    {
        return view('users.wallet.index');
    }

    public function reference(Request $request)
    {
        return view('users.reference.index');
    }
}
