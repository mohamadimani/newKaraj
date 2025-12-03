<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Clue;
use App\Models\ClueOnlineCourse;
use App\Models\FamiliarityWay;
use App\Models\OnlineCourse;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClueController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->name or !$request->mobile or !$request->professionId) {
            return apiResponse()
                ->message(__('اطلاعات به درستی ارسال نشده اند'))
                ->data($request->all())
                ->send(400);
        }

        if (!OnlineCourse::find($request->professionId)) {
            return apiResponse()
                ->message(__('دوره وجود ندارد'))
                ->data($request->all())
                ->send(400);
        }

        if (!preg_match('/^[0-9]{11}+$/', $request->mobile)) {
            return apiResponse()
                ->message(__('موبایل صحیح نیست'))
                ->data($request->all())
                ->send(400);
        }

        if ($user = User::where('mobile', $request->mobile)->first()) {

            if ($clueOnlineCourse = ClueOnlineCourse::where([
                'clue_id' => $user->clue->id,
                'online_course_id' => $request->professionId,
            ])->first()) {
                return apiResponse()
                    ->message(__('این دوره قبلا ثبت شده'))
                    ->data($request->all())
                    ->send(400);
            } else {
                $clueOnlineCourse =  $this->addClueOnlineCourse($user->clue, $request);
                return apiResponse()
                    ->message(__('دوره با موفقیت ثبت شد'))
                    ->data($request->all())
                    ->send(200);
            }
        } else {
            DB::beginTransaction();

            $user = $this->createUser($request);
            $clue = $this->createClue($user, $request);
            if ($clue) {
                DB::commit();
                return apiResponse()
                    ->message(__('کاربر با موفقیت ثبت شد'))
                    ->data($clue)
                    ->send(200);
            }
            DB::rollBack();
            return apiResponse()
                ->message(__('مشکل در ثبت اطلاعات'))
                ->data()
                ->send(500);
        }
    }

    private function createUser(Request $request): User
    {
        $name = explode(' ', $request->name);
        $firstName = $name[0];
        $name[0] = null;
        $lastName = implode(' ', $name);

        return  User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'mobile' => $request->mobile,
            'is_admin' => false,
            'is_active' => true,
            'created_by' => FIDAR_AI(),
        ]);
    }

    private function createClue(User $user, $request): Clue
    {
        $clue = Clue::create([
            'user_id' => $user->id,
            'created_by' => FIDAR_AI(),
            'branch_id' => 6,
            'secretary_id' =>  Secretary::where('user_id', FIDAR_AI())->first()->id,
            'familiarity_way_id' => FamiliarityWay::where('slug', 'site')->first()->id,
        ]);

        $this->addClueOnlineCourse($clue, $request);
        $clue->professions()->sync([1]);
        return $clue;
    }

    private function addClueOnlineCourse(Clue $clue, $request)
    {
        return ClueOnlineCourse::create([
            'clue_id' => $clue->id,
            'online_course_id' => $request->professionId,
            'order_id' => null,
            'order_item_id' => null,
            'created_by' => FIDAR_AI(),
        ]);
    }
}
