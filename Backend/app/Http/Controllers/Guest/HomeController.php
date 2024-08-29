<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $projects = Project::whereIsCompleted(true)->orderByDesc('created_at')->paginate(5);

        return view('guest.home', compact('projects'));
    }
}
