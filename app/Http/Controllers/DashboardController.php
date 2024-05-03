<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboard(){
        $totalUsers = DB::select('SELECT count(id) AS count FROM users');
        $totalProjects = DB::select('SELECT count(id) AS count FROM projects');
        $totalTransactions = DB::select('SELECT count(*) AS count FROM transactions');
        return view('dashboard',['totalUsers'=>$totalUsers,'totalProjects'=>$totalProjects,'totalTransactions'=>$totalTransactions]);
    }
}
