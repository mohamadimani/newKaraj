<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\FollowUp\FollowUpRepository;
use App\Repositories\User\ClueRepository;
use App\Repositories\User\SalesTeamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::user();
        if (optional($loggedInUser)->isSecretary()) {
            return $this->renderSecretaryView();
        } elseif (isAdminNumber()) {
            return $this->renderAdminView();
        } elseif (optional($loggedInUser)->isclerk()) {
            return $this->renderSecretaryView();
        } elseif (optional($loggedInUser)->isteacher()) {
            return $this->renderTeacherView();
        }
    }

    private function renderAdminView()
    {
        return view('admin.panel.index');
    }

    private function renderSecretaryView()
    {
        return view('admin.panel.secretary');
    }

    private function renderTeacherView()
    {
        return view('admin.panel.teacher');
    }
}
