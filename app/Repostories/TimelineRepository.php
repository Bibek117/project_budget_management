<?php

namespace App\Repostories;

use App\Models\Timeline;

class TimelineRepository extends CommonInterfaceRepository
{
    public function __construct(Timeline $timeline)
    {
        parent::__construct($timeline);
    }
}

?>