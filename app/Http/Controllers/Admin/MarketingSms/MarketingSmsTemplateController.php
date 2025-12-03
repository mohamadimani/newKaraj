<?php

namespace App\Http\Controllers\Admin\MarketingSms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MarketingSms\MarketingSmsTemplateRequest;
use App\Models\Branch;
use App\Models\MarketingSms\MarketingSmsTemplate;
use App\Models\Profession;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MarketingSmsTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        Gate::authorize('index', MarketingSmsTemplate::class);

        return view('admin.marketing-sms-templates.index');
    }

    /**
     * Display the specified resource.
     *
     * @param MarketingSmsTemplate $marketingSmsTemplate
     * @return \Illuminate\Contracts\View\View
     */
    public function settings(MarketingSmsTemplate $marketingSmsTemplate)
    {
        Gate::authorize('settings', $marketingSmsTemplate);

        return view('admin.marketing-sms-templates.settings', compact('marketingSmsTemplate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        Gate::authorize('create', MarketingSmsTemplate::class);

        return view('admin.marketing-sms-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MarketingSmsTemplateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MarketingSmsTemplateRequest $request)
    {
        Gate::authorize('store', MarketingSmsTemplate::class);

        $data = $request->validated();

        MarketingSmsTemplate::createObject($data);

        return redirect()->route('marketing-sms-templates.index')->with('success', __('marketing_sms_templates.messages.successfully_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketingSmsTemplate $marketingSmsTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MarketingSmsTemplate $marketingSmsTemplate
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(MarketingSmsTemplate $marketingSmsTemplate)
    {
        Gate::authorize('edit', $marketingSmsTemplate);

        $branches = Branch::query()->active()->get();
       
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        return view('admin.marketing-sms-templates.edit', compact('marketingSmsTemplate', 'branches', 'professions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MarketingSmsTemplateRequest $request
     * @param MarketingSmsTemplate $marketingSmsTemplate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MarketingSmsTemplateRequest $request, MarketingSmsTemplate $marketingSmsTemplate)
    {
        Gate::authorize('update', $marketingSmsTemplate);

        $data = $request->validated();

        $marketingSmsTemplate::updateObject($marketingSmsTemplate, $data);

        return redirect()->route('marketing-sms-templates.index')->with('success', __('marketing_sms_templates.messages.successfully_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarketingSmsTemplate $marketingSmsTemplate)
    {
        //
    }
}
