<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function index()
    {
        return view('users.gifts.index');
    }
}
