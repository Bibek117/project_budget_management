<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSubCategory extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function accountcat(){
        return $this->belongsTo(AccountCategory::class,'account_category_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class,'coa_id');
    }


}
