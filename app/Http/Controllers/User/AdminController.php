<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('check_admin',['only'=>['imitateLogin']]);
    }
    //login as Other
    public function imitateLogin(Request $request, $id)
    {
        try {
            $Admin = Auth::user();
            $request->session()->put(['admin_id' => $Admin->id, 'is_imitating' => true]);
            $user = User::findOrFail($id);
            Auth::logout();
            Auth::loginUsingId($user->id);
            return view('dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function imitateLogout(Request $request)
    {
        try {
            if (
                $request->session()->exists('admin_id')  &&
                $request->session()->exists('is_imitating')
            ) {
                $admin_id = $request->session()->pull('admin_id',null);
                $request->session()->forget('is_imitating');
                Auth::logout();
                Auth::loginUsingId($admin_id);
                $projects = Project::whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->get();
                $totalUsers = DB::select('SELECT count(id) AS count FROM users');
                $totalProjects = DB::select('SELECT count(id) AS count FROM projects');
                $totalTransactions = DB::select('SELECT count(*) AS count FROM transactions');
                return view('dashboard', ['totalUsers' => $totalUsers, 'totalProjects' => $totalProjects, 'totalTransactions' => $totalTransactions,'activeProject' => $projects[0]]);
            }else{
                abort(403);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
