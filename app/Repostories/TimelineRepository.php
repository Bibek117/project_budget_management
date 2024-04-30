<?php

namespace App\Repostories;

use App\Models\Timeline;

class TimelineRepository extends CommonInterfaceRepository
{
    public function __construct(Timeline $timeline)
    {
        parent::__construct($timeline);
    }

    //override parent create
    public function create($request){
        
        $project_id = $request->project_id;
        $timelineData = [];

        foreach ($request->timelines as $timeline) {
            $timelineData[] = [
                'project_id' => $project_id,
                'title' => $timeline['title'],
                'start_date' => $timeline['start_date'],
                'end_date' => $timeline['end_date']
            ];
        }

        Timeline::insert($timelineData);

    }
}

?>