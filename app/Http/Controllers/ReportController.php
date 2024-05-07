<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use App\Models\Contacttype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    //aging report
    public function ageingReportForm(){
        return view('reports.ageingReport');
    }

    public function recordDetailForm()
    {
        return view('reports.RecordDetailReport', ['contacttypes' => Contacttype::all(), 'coaCategory' => AccountCategory::all()]);
    }

    public function recordDetail(Request $request)
    {
        $validatedData = $request->validate([
            'contacttype_id' => 'required',
            'coa' => 'required'
        ]);
        // return response()->json(['validate'=>$validatedData]);

        $result = DB::select('SELECT 
                                r.code, 
                                CONCAT_WS("-", ct.name, users.username) AS Contact, 
                                CONCAT_WS("-", acat.name, ascat.name) AS ChartOfAcc,
                                CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE NULL END AS credit,
                                CASE WHEN t.amount >= 0 THEN t.amount ELSE NULL END AS debit,
                                @balance := @balance + t.amount AS balance
                              FROM 
                                transactions AS t
                                INNER JOIN contacts AS c ON c.id = t.contact_id
                                INNER JOIN users ON users.id = c.user_id
                                INNER JOIN contacttypes AS ct ON ct.id = c.contacttype_id
                                INNER JOIN records AS r ON r.id = t.record_id
                                LEFT JOIN budgets AS b ON b.id = t.budget_id
                                INNER JOIN account_sub_categories AS ascat ON ascat.id = t.coa_id
                                INNER JOIN account_categories AS acat ON acat.id = ascat.account_category_id
                                CROSS JOIN (SELECT @balance := 0) AS init
                              WHERE 
                                acat.id = ? AND ct.id = ?', [$validatedData['contacttype_id'], $validatedData['coa']]);
        return response()->json(['result' => $result]);
    }



    public function contactPayableReceivableForm()
    {

        return view('reports.ContactPayableReceiveable', ['contacttypes' => Contacttype::all(),'coaCategory'=>AccountCategory::all()]);
    }

    public function contactPayableReceivable(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['message' => 'Call successs']);
        }
    }
}





// SELECT r.code,concat_ws("-",ct.name,users.username) AS Contact,concat_ws("-",acat.name,ascat.name) AS ChartOfAcc,
//                                CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE NULL END AS credit, 
//                                CASE WHEN t.amount >=0 THEN t.amount ELSE NULL END AS  debit,
//                                SUM(t.amount) OVER (ORDER BY t.id) AS balance 
//                                FROM transactions AS t INNER JOIN contacts AS c ON c.id = t.contact_id 
//                                INNER JOIN users ON users.id = c.user_id 
//                                INNER JOIN  contacttypes AS ct ON ct.id = c.contacttype_id 
//                                INNER JOIN records AS r ON r.id = t.record_id 
//                                LEFT JOIN budgets AS b ON b.id = t.budget_id 
//                                INNER JOIN account_sub_categories AS ascat ON ascat.id = t.coa_id 
//                                INNER JOIN  account_categories AS acat ON acat.id = ascat.account_category_id 
//                                WHERE acat.id = ? AND ct.id = ?

// WITH contact_type AS (SELECT id, name FROM contacttypes WHERE id = 2), account_category AS (SELECT id, name FROM account_categories WHERE id = 2)
//r.code,concat_ws("-",ct.name,users.username) AS Contact,concat_ws("-",acat.name,ascat.name) AS ChartOfAcc,
//                                CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE NULL END AS credit, 
//                                CASE WHEN t.amount >=0 THEN t.amount ELSE NULL END AS  debit,
//                                SUM(t.amount) OVER (ORDER BY t.id) AS balance 
//                                FROM transactions AS t INNER JOIN contacts AS c ON c.id = t.contact_id 
//                                INNER JOIN users ON users.id = c.user_id 
//                                INNER JOIN  contacttypes AS ct ON ct.id = c.contacttype_id 
//                                INNER JOIN records AS r ON r.id = t.record_id 
//                                LEFT JOIN budgets AS b ON b.id = t.budget_id 
//                                INNER JOIN account_sub_categories AS ascat ON ascat.id = t.coa_id 
//                                INNER JOIN  account_categories AS acat ON acat.id = ascat.account_category_id 
//                                WHERE acat.id = ? AND ct.id = ?







//initial
// SELECT
//                                 t.amount,
//                                 t.coa_id,
//                                 records.code,
//                                 CategoryName,
//                                 SubCatName,
//                          users.username,
//                        ct.name AS contacttype,
//                             CASE WHEN t.amount < 0 THEN t.amount ELSE NULL END AS credit,
//                             CASE WHEN t.amount >= 0 THEN t.amount ELSE NULL END AS debit,
//                             SUM(t.amount) OVER (ORDER BY t.id) AS balance
//                     FROM
//                           (SELECT id, name FROM contacttypes WHERE id = ?) AS ct
//                           INNER JOIN contacts ON ct.id = contacts.contacttype_id
//                             INNER JOIN (SELECT id, username FROM users) AS users ON users.id = contacts.user_id
//                              INNER JOIN
//                         (SELECT
//                                   t.id,
//                                   t.amount,
//                                    t.coa_id,
//                                    t.budget_id,
//                                  ac.name AS CategoryName,
//                                  subcat.name AS SubCatName,
//                                   t.record_id,
//                                   t.contact_id
//                                       FROM
//                                        transactions AS t
//                                       INNER JOIN account_sub_categories AS subcat ON subcat.id = t.coa_id
//                                      INNER JOIN (SELECT id, name FROM account_categories WHERE id = ?) AS ac ON ac.id = subcat.account_category_id
//                             ) AS t ON contacts.id = t.contact_id
//                          LEFT JOIN (SELECT id, name FROM budgets) AS budgets ON budgets.id = t.budget_id
//                           INNER JOIN records ON records.id = t.record_id;






// SELECT 
//     code, 
//     Contact, 
//     ChartOfAcc,
//     credit,
//     debit,
//     balance
// FROM (
//     SELECT 
//         r.code, 
//         CONCAT_WS("-", ct.name, users.username) AS Contact, 
//         CONCAT_WS("-", acat.name, ascat.name) AS ChartOfAcc,
//         CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE NULL END AS credit,
//         CASE WHEN t.amount >= 0 THEN t.amount ELSE NULL END AS debit,
//         @balance := @balance + t.amount AS balance,
//         @cumulative_sum := @cumulative_sum + t.amount AS cumulative_sum
//     FROM 
//         transactions AS t
//         INNER JOIN contacts AS c ON c.id = t.contact_id
//         INNER JOIN users ON users.id = c.user_id
//         INNER JOIN contacttypes AS ct ON ct.id = c.contacttype_id
//         INNER JOIN records AS r ON r.id = t.record_id
//         LEFT JOIN budgets AS b ON b.id = t.budget_id
//         INNER JOIN account_sub_categories AS ascat ON ascat.id = t.coa_id
//         INNER JOIN account_categories AS acat ON acat.id = ascat.account_category_id
//         CROSS JOIN (SELECT @balance := 0, @cumulative_sum := 0) AS init
//     WHERE 
//         acat.id = 2 AND ct.id = 2
//     ORDER BY 
//         r.code ASC
// ) AS subquery
// ORDER BY 
//     cumulative_sum DESC;
