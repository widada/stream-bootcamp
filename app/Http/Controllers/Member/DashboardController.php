<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class DashboardController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('featured', 'DESC')
        ->orderBy('created_at', 'DESC')
        ->get();

        // dd($movies);

        return view('member.dashboard', ['movies' => $movies]);
    }
}
