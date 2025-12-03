<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsReportStoreRequest;
use App\Http\Requests\GoodsStoreRequest;
use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Goods;
use App\Models\GoodsReport;
use Illuminate\Support\Facades\Gate;

class GoodsController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Goods::class);
        return view('admin.goods.index');
    }

    public function create()
    {
        Gate::authorize('create', Goods::class);
        $branches = Branch::active()->get();
        $classRooms = ClassRoom::active()->get();
        return view('admin.goods.create', compact('branches', 'classRooms'));
    }

    public function store(GoodsStoreRequest $request)
    {
        Gate::authorize('store', Goods::class);
        $image = $request->file('image');
        $imageName = SaveImage($image, 'goods');

        $goods = Goods::create([
            'name' => $request->name,
            'code' => $request->code,
            'count' => $request->count,
            'description' => $request->description,
            'branch_id' => $request->branch_id,
            'class_room_id' => $request->class_room_id,
            'image' => $imageName,
            'created_by' => user()->id,
            'health_status' => $request->health_status,
        ]);
        if ($goods) {
            $report = GoodsReport::create([
                'goods_id' => $goods->id,
                'branch_id' => $goods->branch_id,
                'class_room_id' => $goods->class_room_id,
                'count' => $goods->count,
                'health_status' => $goods->health_status,
                'description' => $goods->description,
                'image' => $imageName,
                'created_by' => user()->id,
            ]);
            return redirect()->route('goods.create')->with('success', 'با موفقیت ثبت شد');
        } else {
            return redirect()->route('goods.create')->with('error', 'خطایی رخ داده است');
        }
    }

    public function reportsStore(GoodsReportStoreRequest $request, Goods $goods)
    {
        Gate::authorize('reportsStore', Goods::class);
        $image = $request->file('image');
        $imageName = SaveImage($image, 'goods');

        $report = GoodsReport::create([
            'goods_id' => $goods->id,
            'branch_id' => $goods->branch_id,
            'class_room_id' => $goods->class_room_id,
            'teacher_id' => $request->teacher_id,
            'count' => $request->count,
            'health_status' => $request->health_status,
            'description' => $request->description,
            'image' => $imageName,
            'created_by' => user()->id,
        ]);
        if ($report) {
            $goods->update([
                'count' => $request->count,
                'health_status' => $request->health_status,
                'description' => $request->description,
                'image' => $imageName,
                'created_by' => user()->id,
            ]);
            return redirect()->route('goods.index', $goods->id)->with('success', 'با موفقیت ثبت شد');
        } else {
            return redirect()->route('goods.index', $goods->id)->with('error', 'خطایی رخ داده است');
        }
    }
}
