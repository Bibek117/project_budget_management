<?php
namespace App\Repostories;

use App\Interfaces\CommonInterface;
use Illuminate\Database\Eloquent\Model;

class CommonInterfaceRepository implements CommonInterface{

    protected $model; //protected
    //constructor
    public function __construct(Model $model){
        $this->model = $model;
    }
  

    public function getAll(){
        return  $this->model->all();
    }

    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
       
    }

    public function create(array $data)
    {
        return $this->model->create($data);
       
    }

    public function updateById(int $id, array $data)
    {
        return $this->model->where('id',$id)->update($data);
       
    }

    public function deleteById(int $id)
    {
        return $this->model->where('id',$id)->delete();
        
    }

}

?>


