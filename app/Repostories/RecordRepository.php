<?php

namespace App\Repostories;

use App\Models\Record;
use App\Models\Timeline;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Repostories\CommonInterfaceRepository;

class RecordRepository extends CommonInterfaceRepository
{
    public function __construct(Record $record)
    {
        parent::__construct($record);
    }

    public function updateOrCreateTransactions(array $request, int $recordId)
    {
        foreach ($request['transactions'] as $transaction) {
            Transaction::updateOrCreate(
                //where
                ['id' => $transaction['id'] ?? null],
                //update or create
                [
                    'record_id' => $recordId,
                    'contact_id' => $transaction['contact_id'],
                    'budget_id' => $transaction['budget_id'] ?? null,
                    'coa_id' => $transaction['coa_id'],
                    'amount' => $transaction['amount'],
                    'desc' => $transaction['desc']
                ]
            );
        }
    }

    //get timeline given execution date
    public function getTimeline($transactionDate){
        return Timeline::where('start_date', '<=', $transactionDate)
        ->where('end_date', '>=', $transactionDate)
         ->get();
    }
}
