<?php
namespace App\Repostories;

use App\Models\Contacttype;

class ContacttypeRepository extends CommonInterfaceRepository {
    public function __construct(Contacttype $contacttype){
        parent::__construct($contacttype);
    }


    //override create
    public function create(array $request){
        $contactData = [];
        foreach ($request['contacttypes'] as $contacttype) {
            $contactData[] = ['name' => $contacttype['name']];
        }
        Contacttype::insert($contactData);
    }
}