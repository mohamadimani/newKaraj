<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FollowUpStoreRequest;
use App\Models\FollowUp;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.follow-ups.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FollowUpStoreRequest $request)
    {
        FollowUp::create([
            ...$request->validated(),
            'remember_time' => $request->remember_time ? toGeorgianDate($request->remember_time) : null,
            'created_by' => Auth::id(),
            'step' => 'step1',
        ]);

        return redirect()->back()->with('success', __('follow_ups.successfully_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(FollowUp $followUp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FollowUp $followUp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FollowUp $followUp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FollowUp $followUp)
    {
        //
    }
}
