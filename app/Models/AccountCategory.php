<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['accountsubcat'];

    public function accountsubcat(){
        return $this->hasMany(AccountSubCategory::class, 'account_category_id');
    }
}
