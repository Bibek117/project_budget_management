<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Timeline;

class Budget extends Model
{
    use HasFactory;
    
    protected $fillable = ['timeline_id','name','amount'];

    //relationships
    //budget belongsTo timeline
    public function timeline(){
        return $this->belongsTo(Timeline::class,'timeline_id');
    }


    //budget hasmany
    public function transaction(){
        return $this->hasMany(Transaction::class,'budget_id');
    }
}
