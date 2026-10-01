<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function estudiante()
    {
        return view('dashboards.estudiante');
    }

    public function tutor()
    {
        return view('dashboards.tutor');
    }

    public function admin()
    {
        return view('dashboards.admin');
    }
}
