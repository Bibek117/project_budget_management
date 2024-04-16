<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Repostories\RecordRepository;

class RecordController extends Controller
{
    private $recordRepo;
   
    public function __construct(RecordRepository $recordRepository){
        $this->recordRepo = $recordRepository;
    }


    //index
    public function index(){
        $result = $this->recordRepo->getAll();
       return view('transactions.index',['records'=>$result]);
    }


    //show single record i.e has multiple transactions

    public function show($id){
        $transactionsInRecord = Transaction::where('record_id',$id)->latest()->get();
        return view('transactions.show',['transactionsInRecord'=>$transactionsInRecord]);
    }

    //show transaction edit form

    public function edit($id){
        $transactionsInRecord = Transaction::where('record_id', $id)->latest()->get();
        return view('transactions.update',['transactionsInRecord'=>$transactionsInRecord,'record'=>$this->recordRepo->getById($id)]);
    }

    //delete record
    public function destroy($id){
        $res = $this->recordRepo->deleteById($id);
        return redirect()->route('record.index')->withSuccess("Record Deleted Successfully");
        }


    public function createRecord(Request $request){
        $validatedRequest = $request->validate([
            'user_id'=>'required',
            'project_id'=>'required'
        ]);
        try {
            $result = $this->recordRepo->create($validatedRequest);
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
    }
}
