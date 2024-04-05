<?php

namespace App\Repostories;

use App\Models\Record;

class RecordRepository extends CommonInterfaceRepository{
    public function __construct(Record $record){
        parent::__construct($record);
    }
}