<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacttype extends Model
{
    use HasFactory;

    use HasFactory;
    protected $fillable = ['name'];

    //hasmany with contacts
    public function contact()
    {
        return $this->hasMany(Contact::class, 'contacttype_id');
    }
}
