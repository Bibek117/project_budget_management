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

    public function recordDetailForm()
    {
        return view('reports.RecordDetailReport', ['contacttypes' => Contacttype::all(),'coaCategory'=>AccountCategory::all()]);
    }

    public function recordDetail(Request $request)
    {
        $validatedData = $request->validate([
            'contacttype_id' => 'required',
            'coa' => 'required'
        ]);

        $result = DB::select(' SELECT
        t.amount,
        t.coa_id,
        CategoryName,
        SubCatName,
        budgets.name,
        records.code,
        users.username,
        ct.name AS contacttype,
        CASE WHEN t.amount < 0 THEN t.amount ELSE NULL END AS credit,
         CASE WHEN t.amount >= 0 THEN t.amount ELSE NULL END AS debit,
         SUM(ABS(t.amount)) OVER (ORDER BY t.id) AS balance
     FROM
        (SELECT id, name FROM contacttypes WHERE id = 1) AS ct
        INNER JOIN contacts ON ct.id = contacts.contacttype_id
        INNER JOIN (SELECT id, username FROM users) AS users ON users.id = contacts.user_id
        INNER JOIN
            (SELECT
                 t.id,
                 t.amount,
                  t.coa_id,
                  t.budget_id,
                  ac.name AS CategoryName,
                  subcat.name AS SubCatName,
                 t.record_id ,t.contact_id
              FROM
                  transactions AS t
                  INNER JOIN account_sub_categories AS subcat ON subcat.id = t.coa_id
                 INNER JOIN (SELECT id, name FROM account_categories WHERE id = 1) AS ac ON ac.id = subcat.account_category_id
            ) AS t
         ON contacts.id = t.contact_id
         INNER JOIN (SELECT id, name FROM budgets) AS budgets ON budgets.id = t.budget_id
         INNER JOIN records ON records.id = t.record_id', [$validatedData['contacttype_id'], $validatedData['coa']]);

        return response()->json(['result'=>$result]);
    }



    public function contactPayableReceivableForm()
    {

        return view('reports.ContactPayableReceiveable', ['contacttypes' => Contacttype::all()]);
    }

    public function contactPayableReceivable(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['message' => 'Call successs']);
        }
    }
}
