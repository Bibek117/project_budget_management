<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Budget;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = ['project_id','title','start_date','end_date'];

    //relationships

    //timeline hasmany budgets
    protected $with =['budget'];

    public function budget(){
        return $this->hasMany(Budget::class,'timeline_id');
    }

    //timeline belongsto project
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }
}
