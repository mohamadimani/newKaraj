<?php

namespace App\Http\Controllers\Admin\MarketingSms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MarketingSms\MarketingSmsItemStoreRequest;
use App\Http\Requests\Admin\MarketingSms\MarketingSmsItemUpdateRequest;
use App\Models\MarketingSms\MarketingSmsItem;
use App\Models\MarketingSms\MarketingSmsTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MarketingSmsItemController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(MarketingSmsTemplate $marketingSmsTemplate)
    {
        Gate::authorize('create', MarketingSmsItem::class);

        return view('admin.marketing-sms-items.create', compact('marketingSmsTemplate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarketingSmsItemStoreRequest $request)
    {
        $afterTime = $this->getAfterTimeArray($request);
        $afterTimeSeconds = $this->calculateTotalSeconds($afterTime);
        if ($afterTimeSeconds == 0) {
            return back()->with('error', __('marketing_sms_items.messages.error_after_time_zero'));
        }

        $marketingSmsItem = MarketingSmsItem::create([
            'marketing_sms_template_id' => $request->marketing_sms_template_id,
            'content' => $request->content,
            'is_active' => $request->is_active,
            'include_params' => $this->getIncludeParams($request),
            'after_time' => $afterTimeSeconds,
            'after_time_details' => $afterTime,
            'created_by' => Auth::id(),
        ]);

        return $this->redirectToSettings($marketingSmsItem, 'successfully_created');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarketingSmsItem $marketingSmsItem)
    {
        Gate::authorize('edit', $marketingSmsItem);

        return view('admin.marketing-sms-items.edit', compact('marketingSmsItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarketingSmsItemUpdateRequest $request, MarketingSmsItem $marketingSmsItem)
    {
        Gate::authorize('update', $marketingSmsItem);

        $afterTime = $this->getAfterTimeArray($request);
        $afterTimeSeconds = $this->calculateTotalSeconds($afterTime);
        if ($afterTimeSeconds == 0) {
            return back()->with('error', __('marketing_sms_items.messages.error_after_time_zero'));
        }

        $marketingSmsItem->update([
            'content' => $request->content,
            'is_active' => $request->is_active,
            'include_params' => $this->getIncludeParams($request),
            'after_time' => $afterTimeSeconds,
            'after_time_details' => $afterTime,
            'created_by' => Auth::id(),
        ]);

        return $this->redirectToSettings($marketingSmsItem, 'successfully_updated');
    }

    /**
     * Get array of time units from request
     */
    private function getAfterTimeArray($request): array
    {
        return [
            'seconds' => $request->after_time_seconds ?? 0,
            'minutes' => $request->after_time_minutes ?? 0,
            'hours' => $request->after_time_hours ?? 0,
            'days' => $request->after_time_days ?? 0,
            'months' => $request->after_time_months ?? 0,
            'years' => $request->after_time_years ?? 0,
        ];
    }

    /**
     * Get include parameters array from request
     */
    private function getIncludeParams($request): array
    {
        return [
            'show_student_name' => $request->show_student_name,
            'show_secretary_name' => $request->show_secretary_name,
            'show_branch_name' => $request->show_branch_name,
            'discount_amount' => $request->discount_amount,
        ];
    }

    /**
     * Calculate total seconds from time units array
     */
    private function calculateTotalSeconds(array $afterTime): int
    {
        return (int) (
            ($afterTime['seconds'] ?? 0) +
            ($afterTime['minutes'] ?? 0) * 60 +
            ($afterTime['hours'] ?? 0) * 3600 +
            ($afterTime['days'] ?? 0) * 86400 +
            ($afterTime['months'] ?? 0) * 2592000 +
            ($afterTime['years'] ?? 0) * 31536000
        );
    }

    /**
     * Redirect to settings page with success message
     */
    private function redirectToSettings(MarketingSmsItem $marketingSmsItem, string $messageKey)
    {
        return redirect()
            ->route('marketing-sms-templates.settings', $marketingSmsItem->marketing_sms_template_id)
            ->with('success', __("marketing_sms_items.messages.{$messageKey}"));
    }
}
