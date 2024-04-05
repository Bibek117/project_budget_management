<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Timeline;

class Project extends Model
{
    use HasFactory;

    //avoid mass assignmanets blocking
    protected $fillable = ['title','desc','start_date','end_date'];

    //protected $guarded = [];

    //relationships
    protected $with =['timeline'];

    //project hasmany timelines
    public function timeline(){
       return $this->hasMany(Timeline::class,'project_id');
    }


    //many to many with users
    public function record()
    {
        return $this->hasMany(Record::class, 'project_id');
    }

    public function users(){
        return $this->belongsToMany(User::class,'project_user');
    }

}
