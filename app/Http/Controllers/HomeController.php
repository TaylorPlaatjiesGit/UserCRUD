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

        $data['users'] = User::with('interests')->paginate(10);
        $data['languages'] = Language::orderBy('language')->get();
        $data['interests'] = Interest::orderBy('interest')->get();

        return view('home', $data);
    }
}