<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard');
    }

    public function getDiskUsage()
    {
        $dt = round(disk_total_space("C:") / 1024 / 1024 / 1024, 2);
        $df = round(disk_free_space("C:") / 1024 / 1024 / 1024, 2);
        $du = $dt - $df;

        // $dt = round(disk_total_space("var/www") / 1024 / 1024 / 1024, 2);
        // $df = round(disk_free_space("var/www") / 1024 / 1024 / 1024, 2);
        // $du = $dt - $df;

        $data = [
            'total' => ['value' => $dt, 'percentage' => 100],
            'free' => ['value' => $df, 'percentage' => round($df * 100 / $dt, 2)],
            'used' => ['value' => $du, 'percentage' => round($du * 100 / $dt, 2)]
        ];

        return $data;
    }
}
