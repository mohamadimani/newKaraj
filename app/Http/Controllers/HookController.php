<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePayment;
use App\Models\OnlinePayment;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;

class HookController extends Controller
{
    public function addBranchIdInCourseSession()
    {
        $courses = Course::get();
        foreach ($courses as $course) {
            foreach ($course->courseSessions as $courseSession) {
                $courseSession->update(['branch_id' => $course->branch_id]);
            }
        }
        dd('ok');
    }

    public function addFidarAiIdInOnlinePaymentCreateByUser()
    {
        $onlinePayments = OnlinePayment::get();
        foreach ($onlinePayments as $onlinePayment) {
            if ($onlinePayment->user_id == $onlinePayment->created_by) {
                $onlinePayment->update(['created_by' => FIDAR_AI()]);
            }
        }
        dd('ok');
    }
    public function addFidarAiIdInCoursePaymentCreateByUser()
    {
        $coursePayments = CoursePayment::get();
        foreach ($coursePayments as $coursePayment) {
            if ($coursePayment->user_id == $coursePayment->created_by) {
                $coursePayment->update(['created_by' => FIDAR_AI()]);
            }
        }
        dd('ok');
    }
    public function addStudentAccountForOnlineCourseUsers()
    {
        $doneItemCount = 0;
        $orderItems = OrderItem::active()->where('pay_date', 'IS NOT', null)->with('user')->orderBy('created_at', 'desc')->get();
        foreach ($orderItems as $orderItem) {
            $user = User::find($orderItem->user_id);
            addClueToStudent($user);
            $doneItemCount++;
        }
        dd($doneItemCount);
    }
}
