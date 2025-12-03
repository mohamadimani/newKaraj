<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiscountStoreRequest;
use App\Http\Requests\Admin\DiscountUpdateRequest;
use App\Models\Course;
use App\Models\Discount;
use App\Models\Profession;
use App\Models\User;
use App\Models\OnlineCourse;
use App\Repositories\Profession\ProfessionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('index', Discount::class);
        return view('admin.discounts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Discount::class);
        $users = User::query()->whereHas('clue')->get();
        
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();
        
        $courses = Course::active()->get();
        $onlineCourses = OnlineCourse::active()->get();
        return view('admin.discounts.create', compact('users', 'professions', 'courses', 'onlineCourses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountStoreRequest $request)
    {
        Gate::authorize('store', Discount::class);
        DB::beginTransaction();
        try {
            $bannerName = null;

            if ($banner = $request->file('banner')) {
                $bannerName = $banner->getClientOriginalName();
                $banner->move(public_path('images/discounts/banners'), $bannerName);
            }

            Discount::create([
                ...$request->validated(),
                'available_from' => toGeorgianDate($request->available_from, true),
                'available_until' => toGeorgianDate($request->available_until, true),
                'created_by' => Auth::id(),
                'banner' => $bannerName
            ]);
            DB::commit();
            return redirect()->route('discounts.index')->with('success', __('discounts.messages.created_successfully'));
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        Gate::authorize('edit', $discount);
        $users = User::query()->whereHas('clue')->get();
        
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();
        
        $courses = Course::active()->get();
        $onlineCourses = OnlineCourse::active()->get();
        return view('admin.discounts.edit', compact('discount', 'users', 'professions', 'courses', 'onlineCourses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscountUpdateRequest $request, Discount $discount)
    {
        Gate::authorize('update', $discount);
        DB::beginTransaction();
        try {

            if ($banner = $request->file('banner')) {
                $bannerName = $banner->getClientOriginalName();
                $banner->move(public_path('images/discounts/banners'), $bannerName);
                $discount->banner = $bannerName;
            }

            $discount->update([
                ...$request->validated(),
                'available_from' => toGeorgianDate($request->available_from, true),
                'available_until' => toGeorgianDate($request->available_until, true),
            ]);
            DB::commit();
            return redirect()->route('discounts.index')->with('success', __('discounts.messages.updated_successfully'));
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
