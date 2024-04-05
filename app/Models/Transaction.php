<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['record_id','budget_id','contact_id','amount','desc','COA'];

    public function record(){
        return $this->belongsTo(Record::class);
    }

    public function contact(){
        return $this->belongsTo(Contact::class);
    }

    public function budget(){
        return $this->belongsTo(Transaction::class);
    }
}
