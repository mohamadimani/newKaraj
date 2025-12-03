<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clue;
use App\Models\CourseRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // return view('admin.reports.index');
    }

    public function paymentChangeLog()
    {
        return view('admin.reports.payment-change-log');
    }

    public function verificationCode()
    {
        return view('admin.reports.verification-code');
    }

    public function courseRegisterChangeLog()
    {
        return view('admin.reports.course-register-change-log');
    }

    public function orderItemChangeLog()
    {
        return view('admin.reports.order-item-change-log');
    }

    public function secretarySales()
    {
        return view('admin.reports.secretary-sales');
    }

    public function secretaryFollows()
    {
        return view('admin.reports.secretary-fallows');
    }

    public function secretarySalesChartData(Request $request)
    {
        $startDate = date('Y-m-d', jalaliToTimestamp($request->startDate));
        $endDate = date('Y-m-d', jalaliToTimestamp($request->endDate) + 86400);

        $clues = Clue::where('secretary_id', $request->secretaryId);
        $student = CourseRegister::where('secretary_id', $request->secretaryId)->whereIn('status', ['registered', 'technical']);
        if ($request->startDate) {
            $clues->where('created_at', '>=', $startDate);
            $student->where('created_at', '>=', $startDate);
        }
        if ($request->endDate) {
            $clues->where('created_at', '<=', $endDate);
            $student->where('created_at', '<=', $endDate);
        }
        $clueResult = $clues->selectRaw("DATE(created_at) as date, COUNT(id) as count")->groupBy('date')->get();
        $clueData = $clueResult->pluck('count', 'date')->toArray();
        $studentResult = $student->selectRaw("DATE(created_at) as date, COUNT(id) as count")->groupBy('date')->get();
        $studentData = $studentResult->pluck('count', 'date')->toArray();


        $startDate = jalaliToTimestamp($request->startDate);
        $endDate = jalaliToTimestamp($request->endDate) + 86400;

        $labelDates = [];
        $clueCount = [];
        $studentCount = [];
        while ($startDate != $endDate) {
            $clueCount[] = $clueData[date('Y-m-d', $startDate)] ?? 0;
            $studentCount[] = $studentData[date('Y-m-d', $startDate)] ?? 0;
            $labelDates[] = georgianToJalali(date('Y-m-d', $startDate));
            $startDate = $startDate + 86400;
        }

        return ['clues' => $clueCount, 'students' => $studentCount, 'label' => $labelDates];
    }

    public function financial()
    {
        return view('admin.reports.financial');
    }

    public function sendSmsLog()
    {
        return view('admin.reports.send-sms-log');
    }
}
