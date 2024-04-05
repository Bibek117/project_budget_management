<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Project;
use App\Models\Contacttype;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repostories\TransactionRepository;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Contact;

class TransactionController extends Controller
{
    private $transactionRepo;

    public function __construct(TransactionRepository $transactionRepo){
        $this->transactionRepo = $transactionRepo;
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
        return redirect()->route('transaction.index')->withSuccess('Transaction created successfully');
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
    public function update(StoreTransactionRequest $request, string $id)
    {
        try {
            $result = $this->transactionRepo->updateById($id, $request->validated());
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
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
