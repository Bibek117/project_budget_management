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
        $timeline = DB::select('SELECT * FROM timelines WHERE ? BETWEEN start_date AND end_date',[$execDate]);
        $transactionsInRecord = Transaction::where('record_id',$id)->latest()->get();
        return view('transactions.show',['transactionsInRecord'=>$transactionsInRecord,'record'=>$record,'timeline'=>$timeline]);
    }

    //show transaction edit form

    public function edit($id){
        $record = $this->recordRepo->getById($id);
        $transactionsInRecord = Transaction::where('record_id', $id)->latest()->get();
        $timeline = Timeline::where('start_date', '<=', $record->execution_date)
        ->where('end_date', '>=', $record->execution_date)
        ->get();

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
        $record = Record::create([
            'user_id' => $transaction_creater_id,
            'project_id' => $project_id,
            'execution_date' => $validatedData->execution_date,
            'code' => $validatedData->code
        ]);
        $data = [];
        foreach ($request->transactions as $transaction) {
            $data[] = [
                'record_id' => $record->id,
                'budget_id' => $transaction['budget_id'] ?? null,
                'contact_id' => $transaction['contact_id'],
                'desc' => $transaction['desc'],
                'amount' => $transaction['amount'],
                'COA' => $transaction['COA'],
            ];
        }
        if (count($data) == 1) {
            Transaction::create($data[0]);
        } else {
            Transaction::insert($data);
        }
        return redirect()->route('record.index')->withSuccess('Record created successfully');
    }


    //update record
    public function update(UpdateRecordRequest $request,$id){
         $this->recordRepo->updateById($id,['code'=>$request->code,'execution_date'=>$request->execution_date]);
        foreach ($request->transactions as $transaction) {
            Transaction::updateOrCreate(
                //where
                ['id'=>$transaction['id']??null],
                //update or create
                [
                    'record_id'=>$id,
                    'contact_id'=>$transaction['contact_id'],
                    'budget_id'=>$transaction['budget_id']?? null,
                    'COA' => $transaction['COA'],
                    'amount' => $transaction['amount'],
                    'desc'=> $transaction['desc']
                    ]
                );
        }
        return redirect()->route('record.index')->withSuccess("Record updated successfully");
    }

}
