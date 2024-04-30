<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Contacttype;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repostories\TransactionRepository;
use App\Http\Requests\StoreTransactionRequest;

class TransactionController extends Controller
{
    private $transactionRepo;

    public function __construct(TransactionRepository $transactionRepo){
        $this->transactionRepo = $transactionRepo;
        $this->middleware('permission:create-transaction|edit-transaction|delete-transaction|view-transaction',['only'=>['show','index']]);
        $this->middleware('permission:create-transaction',['only'=>['store','create']]);
        $this->middleware('permission:edit-transaction',['only'=>['update','edit']]);
        $this->middleware('permission:delete-transaction',['only'=>['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $result = $this->transactionRepo->getAll();
            return view('transactions.index');
        } catch (\Exception $e) {
            return response()->json(['result' => $e->getMessage(), 'success' => false]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('transactions.create', ['projects' => Project::all(),'contacttypes'=>Contacttype::all()]);
    // }

 

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $result = $this->transactionRepo->getById($id);
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e->getMessage(), 'success' => false]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
       
      foreach($request->transactions as $transaction){
            $result = $this->transactionRepo->updateById($transaction['id'],['COA'=>$transaction['COA'],'amount'=>$transaction['amount'],'desc'=>$transaction['desc']]);
      }

      return redirect()->route('record.index')->withSuccess("Record updated successfully");
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $result = $this->transactionRepo->deleteById($id);
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
    }


}
