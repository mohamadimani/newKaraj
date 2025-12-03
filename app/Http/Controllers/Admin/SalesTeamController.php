<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SalesTeam;
use App\Models\Secretary;
use Illuminate\Http\Request;

class SalesTeamController extends Controller
{
    public function index()
    {
        $salesTeams = SalesTeam::all();
        return view('admin.sales-team.index', compact('salesTeams'));
    }

    public function create()
    {
        $secretaries = Secretary::whereNotIn('id', function ($query) {
            $query->select('secretary_id')
                ->from('sales_team_secretaries')->where('sales_team_id', '!=', 5)
                ->whereNull('deleted_at');
        })
            ->whereDoesntHave('user.clerk')
            ->active()->with('user')->get();
        $branches = Branch::active()->get();
        return view('admin.sales-team.create', compact('secretaries', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sales_team_manager_id' => 'required|exists:secretaries,id',
            'branch_id' => 'required|exists:branches,id',
            'monthly_sale_target' => 'nullable|numeric|min:1',
            'secretaries' => 'required|array',
            'secretaries.*' => 'exists:secretaries,id',
            'description' => 'nullable|string'
        ], [
            'secretaries.required' => 'لطفا حداقل یک کارشناس را برای تیم انتخاب کنید',
            'secretaries.*.exists' => 'کارشناس انتخاب شده معتبر نیست',
            'monthly_sale_target.min' => 'تارگت فروش نمیتواند کمتر از 1 باشد',
            'monthly_sale_target.numeric' => 'تارگت فروش باید یک عدد باشد',
            'title.required' => 'لطفا نام تیم را وارد کنید',
            'title.string' => 'نام تیم باید یک متن باشد',
            'title.max' => 'نام تیم نمیتواند بیشتر از 255 کاراکتر باشد',
            'sales_team_manager_id.required' => 'لطفا مدیر تیم را انتخاب کنید',
        ]);

        $salesTeam = SalesTeam::create([
            'title' => $request->title,
            'sales_team_manager_id' => $request->sales_team_manager_id,
            'branch_id' => $request->branch_id,
            'monthly_sale_target' => $request->monthly_sale_target,
            'description' => $request->description,
            'created_by' => auth()->id(),

        ]);

        foreach ($request->secretaries as $secretary) {
            $salesTeam->secretaries()->create([
                'secretary_id' => $secretary
            ]);
        }

        return redirect()->route('sales-team.index')->with('success', 'تیم فروش با موفقیت ایجاد شد');
    }

    public function edit(SalesTeam $salesTeam)
    {
        $secretaries = Secretary::whereNotIn('id', function ($query) use ($salesTeam) {
            $query->select('secretary_id')
                ->from('sales_team_secretaries')
                ->where('sales_team_id', '!=', $salesTeam->id)
                ->where('deleted_at', null);
        })
            ->whereDoesntHave('user.clerk')
            ->active()->with('user')->get();
        $branches = Branch::active()->get();
        return view('admin.sales-team.edit', compact('salesTeam', 'secretaries', 'branches'));
    }

    public function update(Request $request, SalesTeam $salesTeam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sales_team_manager_id' => 'required|exists:secretaries,id',
            'branch_id' => 'required|exists:branches,id',
            'monthly_sale_target' => 'nullable|numeric|min:1',
            'secretaries' => 'required|array',
            'secretaries.*' => 'exists:secretaries,id',
            'description' => 'nullable|string'
        ], [
            'secretaries.required' => 'لطفا حداقل یک کارشناس را برای تیم انتخاب کنید',
            'secretaries.*.exists' => 'کارشناس انتخاب شده معتبر نیست',
            'monthly_sale_target.min' => 'تارگت فروش نمیتواند کمتر از 1 باشد',
            'monthly_sale_target.numeric' => 'تارگت فروش باید یک عدد باشد',
            'title.required' => 'لطفا نام تیم را وارد کنید',
            'title.string' => 'نام تیم باید یک متن باشد',
            'title.max' => 'نام تیم نمیتواند بیشتر از 255 کاراکتر باشد',
            'sales_team_manager_id.required' => 'لطفا مدیر تیم را انتخاب کنید',
        ]);

        $salesTeam->update([
            'title' => $request->title,
            'sales_team_manager_id' => $request->sales_team_manager_id,
            'branch_id' => $request->branch_id,
            'monthly_sale_target' => $request->monthly_sale_target,
            'description' => $request->description,
            'updated_by' => auth()->id(),
        ]);

        $salesTeam->secretaries()->delete();

        foreach ($request->secretaries as $secretary) {
            $salesTeam->secretaries()->create([
                'secretary_id' => $secretary
            ]);
        }
        return redirect()->route('sales-team.index')->with('success', 'تیم فروش با موفقیت ویرایش شد');
    }
}
