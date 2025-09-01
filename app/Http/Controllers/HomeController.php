<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\Language;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $data = [];

        $data['users'] = User::all();
        $data['languages'] = Language::all();
        $data['interests'] = Interest::all();

        return view('home', $data);
    }
}