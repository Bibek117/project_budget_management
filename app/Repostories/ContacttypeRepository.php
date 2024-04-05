<?php
namespace App\Repostories;

use App\Models\Contacttype;

class ContacttypeRepository extends CommonInterfaceRepository {
    public function __construct(Contacttype $contacttype){
        parent::__construct($contacttype);
    }
}