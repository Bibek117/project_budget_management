<?php

namespace App\Repostories;
use App\Models\Transaction;

class TransactionRepository extends CommonInterfaceRepository {
    public function __construct(Transaction $transaction){
        parent::__construct($transaction);
    }
}