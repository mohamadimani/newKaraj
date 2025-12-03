<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClueStoreRequest;
use App\Http\Requests\Admin\ClueUpdateRequest;
use App\Models\Branch;
use App\Models\Clue;
use App\Models\FamiliarityWay;
use App\Models\PhoneInternal;
use App\Models\Province;
use App\Models\User;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('index', Clue::class);
        return view('admin.clues.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Clue::class);

        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        $provinces = Province::orderBy('sort', 'asc')->get();
        $familiarityWays = FamiliarityWay::active()->orderBy('sort', 'asc')->get();
        $internalNumbers = PhoneInternal::active();

        if (Auth::user()->isSecretary()) {
            $internalNumbers = $internalNumbers->where('secretary_id', Auth::user()->secretary->id);
        }

        $internalNumbers = $internalNumbers->with('phone.branch')->get();

        return view('admin.clues.create', compact('professions', 'provinces', 'familiarityWays', 'internalNumbers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClueStoreRequest $request)
    {
        Gate::authorize('store', Clue::class);
        $user = $this->createUser($request);
        $clue = $this->createClue($request, $user);

        $params = [];
        if ($request->redirect_to_course_register) {
            $params = [
                'phone_internal_id' => $request->phone_internal_id,
                'clue_id' => $clue->id,
            ];

            return redirect()->route('course-registers.create', $params)->with('success', __('clues.successfully_created'));
        }

        return redirect()->route('clues.index', $params)->with('success', __('clues.successfully_created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clue $clue)
    {
        Gate::authorize('edit', Clue::class);

        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        $provinces = Province::orderBy('sort', 'asc')->get();
        $familiarityWays = FamiliarityWay::active()->orderBy('sort', 'asc')->get();
        $internalNumbers = PhoneInternal::active()->with('phone.branch')->get();

        $branches = Branch::active()->get();
        return view('admin.clues.edit', compact('clue', 'professions', 'provinces', 'familiarityWays', 'internalNumbers', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClueUpdateRequest $request, Clue $clue)
    {
        Gate::authorize('update', Clue::class);
        $this->updateUser($request, $clue->user);
        $this->updateClue($request, $clue);

        return redirect()->route('clues.index')->with('success', __('clues.successfully_updated'));
    }

    private function createUser(ClueStoreRequest $request): User
    {
        return  User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => formatMobile($request->mobile),
            'gender' => $request->gender,
            'province_id' => $request->province_id,
            'is_active' => true,
            'is_admin' => false,
            'created_by' => Auth::id(),
        ]);
    }

    private function createClue(ClueStoreRequest $request, User $user): Clue
    {
        $phone = PhoneInternal::where('id', $request->phone_internal_id)->with(['phone.branch'])->first();
        $clue = Clue::create([
            'province_id' => $request->province_id,
            'familiarity_way_id' => $request->familiarity_way_id,
            'branch_id' => $phone->phone->branch_id,
            'secretary_id' => $phone->secretary_id,
            'user_id' => $user->id,
            'created_by' => Auth::id(),
        ]);

        $clue->professions()->sync($request->profession_ids);

        return $clue;
    }

    private function updateUser(ClueUpdateRequest $request, User $user): User
    {
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'province_id' => $request->province_id,
        ]);

        return $user;
    }

    private function updateClue(ClueUpdateRequest $request, Clue $clue): Clue
    {
        $clue->update($request->validated());

        $clue->professions()->sync($request->profession_ids);

        return $clue;
    }
}
