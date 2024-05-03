<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Timeline;
use App\Models\Contacttype;
use App\Models\Transaction;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repostories\RecordRepository;
use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use App\Models\AccountCategory;

class RecordController extends Controller
{
    private $recordRepo;

    public function __construct(RecordRepository $recordRepository)
    {
        $this->recordRepo = $recordRepository;
        $this->middleware('permission:view-record|create-record|delete-record|edit-record', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-record', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-record', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-record', ['only' => ['destroy']]);
    }


    //index
    public function index()
    {
        try {
            $result = $this->recordRepo->getAll();
            return view('transactions.index', ['records' => $result]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $result = DB::select('SELECT code FROM records ORDER BY code DESC LIMIT 1');
            $lastRecordCode = explode("-", $result[0]->code)[1];
            return view('transactions.create', ['projects' => Project::all(), 'contacttypes' => Contacttype::all(), 'coaCategory' => AccountCategory::all(), 'lastRecordCode' => $lastRecordCode]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }

    }

    //show single record i.e has multiple transactions
    public function show($id)
    {
        try {
            $record = $this->recordRepo->getById($id);
            $execDate = $record->execution_date;
            $timeline = $this->recordRepo->getTimeline($execDate);
            $transactionsInRecord = Transaction::where('record_id', $id)->latest()->get();
            return view('transactions.show', ['transactionsInRecord' => $transactionsInRecord, 'record' => $record, 'timeline' => $timeline]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }

    //show transaction edit form

    public function edit($id)
    {
        try {
            $record = $this->recordRepo->getById($id);
            $transactionsInRecord = Transaction::where('record_id', $id)->latest()->get();
            $timeline = $this->recordRepo->getTimeline($record->execution_date);
            return view('transactions.update', ['transactionsInRecord' => $transactionsInRecord, 'record' => $record, 'contacttypes' => Contacttype::all(), 'selectedTimeline' => $timeline[0], 'coaCategory' => AccountCategory::all()]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['An error occurred while fetching the record for editing: ' . $e->getMessage()]);
        }
    }

    //delete record
    public function destroy($id)
    {
        try {
            $res = $this->recordRepo->deleteById($id);
            return redirect()->route('record.index')->withSuccess("Record Deleted Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['An error occurred while deleting the record: ' . $e->getMessage()]);
        }
    }



    //store record and transasctions -ajax call
    public function store(StoreRecordRequest $request)
    {
        try {
            $validatedData = $request;
            $transactionCreatorId = Auth::user()->id;
            $projectId = $validatedData->project_id;
            DB::beginTransaction();
            $record = $this->recordRepo->create([
                'user_id' => $transactionCreatorId,
                'project_id' => $projectId,
                'execution_date' => $validatedData->execution_date,
                'code' => $validatedData->code
            ]);
            $this->recordRepo->updateOrCreateTransactions($request->toArray(), $record->id);
            DB::commit();
            return response()->json(['message' => 'Record created successfully', 'success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred while processing the request', 'error' => $e, 'success' => false]);
        }
    }


    //update record
    public function update(UpdateRecordRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $this->recordRepo->updateById($id, ['code' => $request->code, 'execution_date' => $request->execution_date]);
            $this->recordRepo->updateOrCreateTransactions($request->toArray(), $id);
            DB::commit();
            return redirect()->route('record.index')->withSuccess("Record updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Record update Failed' . $e->getMessage()]);
        }
    }
}
