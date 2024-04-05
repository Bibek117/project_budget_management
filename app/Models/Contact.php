<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'contacttype_id'];

    //with
    protected $with = ['transaction'];

    //belongs to

    public function contacttype()
    {
        return $this->belongsTo(Contacttype::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'contact_id');
    }
}
