<?php
namespace App\Repostories;

use App\Models\Contact;

class ContactRepository extends CommonInterfaceRepository{
    public function __construct(Contact $contact){
        parent::__construct($contact);
    }
}