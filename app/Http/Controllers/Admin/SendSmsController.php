<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendGroupSmsRequest;
use App\Http\Requests\Admin\SendSingleSmsRequest;
use App\Jobs\SendSingleSmsJob;

class SendSmsController extends Controller
{
    public function sendGroupSms(SendGroupSmsRequest $request)
    {
        $data = $request->validated();

        foreach ($data['receivers'] as $receiver) {
            dispatch(new SendSingleSmsJob(
                $receiver,
                $data['sms_message']
            ));
        }

        return redirect()->back()->with('success', __('messages.group_sms_sent'));
    }

    public function sendSingleSms(SendSingleSmsRequest $request)
    {
        $data = $request->validated();

        dispatch(new SendSingleSmsJob(
            $data['receiver_user_id'],
            $data['sms_message']
        ));

        return redirect()->back()->with('success', __('messages.single_sms_sent'));
    }
}
