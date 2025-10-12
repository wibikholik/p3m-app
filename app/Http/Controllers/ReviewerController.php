<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewerController extends Controller
{
    public function index()
    {
        return view('reviewer.dashboard');
    }
}
