<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
    protected $fillable  = ['user_id','project_id','code','execution_date'];


    //record belongs to project 
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }

    //record belongs to user
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function transaction(){
        return $this->hasMany(Transaction::class,'record_id');
    }
}
