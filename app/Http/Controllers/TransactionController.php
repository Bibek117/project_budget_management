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
        $this->middleware('auth');
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
    public function create()
    {
        return view('transactions.create', ['projects' => Project::all(),'contacttypes'=>Contacttype::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validatedData = $request;
        $transaction_creater_id = Auth::user()->id;
        $project_id = $validatedData->project_id;
        $record = Record::create([
            'user_id'=>$transaction_creater_id,
            'project_id'=>$project_id
        ]);
        $data = [];
        foreach($request->transactions as $transaction){
            $contact_id = Contact::where('user_id','=',$transaction['user_id'])->where("contacttype_id",'=',$transaction['contacttype_id'])->pluck('id')->first();

            $data[] = [
                'record_id'=>$record->id,
                'budget_id'=>$transaction['budget_id']??null,
                'contact_id'=>$contact_id,
                'desc'=>$transaction['desc'],
                'amount'=>$transaction['amount'],
                'COA'=>$transaction['COA'],
            ];
        }
        if(count($data) == 1){
            Transaction::create($data[0]);
        }else{
            Transaction::insert($data);
        }
        return redirect()->route('record.index')->withSuccess('Record created successfully');
    }

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


    //get users given contact type
    public function getUsersAjax(string $id)
    {
        $result = DB::select('select users.id,users.username from users inner join contacts on users.id = contacts.user_id where contacts.contacttype_id = ?', [$id]);
        return response()->json(['users' => $result]);
    }

}
