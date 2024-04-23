<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Timeline;
use App\Models\Contacttype;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repostories\RecordRepository;
use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;

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
        $record = $this->recordRepo->getById($id);
        $execDate = $record->execution_date;
        $timeline =$this->recordRepo->getTimeline($execDate);
        $transactionsInRecord = Transaction::where('record_id',$id)->latest()->get();
        return view('transactions.show',['transactionsInRecord'=>$transactionsInRecord,'record'=>$record,'timeline'=>$timeline]);
    }

    //show transaction edit form

    public function edit($id){
        $record = $this->recordRepo->getById($id);
        $transactionsInRecord = Transaction::where('record_id', $id)->latest()->get();
        $timeline = $this->recordRepo->getTimeline($record->execution_date);
        return view('transactions.update',['transactionsInRecord'=>$transactionsInRecord,'record'=>$record,'contacttypes'=>Contacttype::all(),'selectedTimeline'=>$timeline[0]]);
    }

    //delete record
    public function destroy($id){
        $res = $this->recordRepo->deleteById($id);
        return redirect()->route('record.index')->withSuccess("Record Deleted Successfully");
        }


    //store record and transasctions
    public function store(StoreRecordRequest $request){
        $validatedData = $request;
        $transaction_creater_id = Auth::user()->id;
        $project_id = $validatedData->project_id;
        $record = $this->recordRepo->create([
            'user_id' => $transaction_creater_id,
            'project_id' => $project_id,
            'execution_date' => $validatedData->execution_date,
            'code' => $validatedData->code
        ]);
        $this->recordRepo->updateOrCreateTransactions($request->toArray(),$record->id);
        return redirect()->route('record.index')->withSuccess('Record created successfully');
    }


    //update record
    public function update(UpdateRecordRequest $request,$id){
         $this->recordRepo->updateById($id,['code'=>$request->code,'execution_date'=>$request->execution_date]);
        $this->recordRepo->updateOrCreateTransactions($request->toArray(),$id);
        return redirect()->route('record.index')->withSuccess("Record updated successfully");
    }

}
