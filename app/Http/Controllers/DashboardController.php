<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboard()
    {
        $projects = Project::whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->get();
        $totalUsers = DB::select('SELECT count(id) AS count FROM users');
        $totalProjects = DB::select('SELECT count(id) AS count FROM projects');
        $totalTransactions = DB::select('SELECT count(*) AS count FROM transactions');
        return view('dashboard', ['totalUsers' => $totalUsers, 'totalProjects' => $totalProjects, 'totalTransactions' => $totalTransactions,'activeProject'=>$projects[0]]);
    }


    public function payRecLineChart(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        try {
            $start_date = $validatedData['start_date'];
            $end_date = $validatedData['end_date'];

            
            $query = "WITH RECURSIVE months AS (
                           SELECT '$start_date' AS month_date
                           UNION ALL
                           SELECT DATE_ADD(month_date,INTERVAL 1 MONTH)
                           FROM months
                           WHERE month_date < '$end_date'
                     ),
                     month_transactions AS (
                        SELECT  YEAR(r.execution_date) AS tyear,
                                MONTH(r.execution_date) AS tmonth,
                                SUM(CASE WHEN acat.name = 'Payables' THEN ABS(t.amount) ELSE 0 END) AS pay,
                                SUM(CASE WHEN acat.name = 'Receivables' THEN ABS(t.amount) ELSE 0 END) AS receive
                        FROM transactions AS t
                             INNER JOIN records AS r ON r.id = t.record_id
                             INNER JOIN account_sub_categories AS asub ON t.coa_id = asub.id
                             INNER JOIN account_categories AS acat ON asub.account_category_id = acat.id 
                        WHERE
                            acat.id in (1,2) AND r.execution_date BETWEEN '$start_date' AND '$end_date'
                        GROUP BY
                            YEAR(r.execution_date),MONTH(r.execution_date)
                     ) SELECT 
                         YEAR(m.month_date) AS year,
                         MONTHNAME(m.month_date) AS month,
                         ROUND(COALESCE(mt.pay,0)) AS payable,
                         ROUND(COALESCE(mt.receive,0)) AS receiveable
                    FROM months AS m
                    LEFT JOIN month_transactions AS mt ON YEAR(m.month_date) = mt.tyear AND MONTH(m.month_date) = mt.tmonth";

            $result = DB::select($query);
            // dd($result);
            return response()->json(['result'=>$result]);   
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}

