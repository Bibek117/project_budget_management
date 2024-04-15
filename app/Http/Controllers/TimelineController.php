<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repostories\TimelineRepository;
use App\Http\Requests\StoreTimelineRequest;
use App\Models\Project;
use App\Models\Timeline;

class TimelineController extends Controller
{
    private $timelineRepo;
    public function __construct(TimelineRepository $timelineRepo){
        $this->timelineRepo = $timelineRepo;
        $this->middleware('auth');
        $this->middleware('permission:create-timeline|edit-timeline|delete-timeline|view-timeline', ['only' => ['show', 'index']]);
        $this->middleware('permission:create-timeline', ['only' => ['store', 'createTimeline']]);
        $this->middleware('permission:edit-timeline', ['only' => ['update', 'edit']]);
        $this->middleware('permission:delete-timeline', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $result = $this->timelineRepo->getAll();
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
    }

    //get timeline for ajax
    public function getSingleAjax(string $id)
    {
        $result = $this->timelineRepo->getById($id);
        return response()->json(['timeline' => $result]);
    }
  
    public function createTimeline(){
        return view('projects.timelines.create',['projects'=>Project::get()]);
    }

    //timeline edit form
    public function edit(String $id)
    {
        $timeline = Timeline::find($id);
        return view('projects.timelines.update', ['timeline' => $timeline]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTimelineRequest $request)
    {
        // dd($request);
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

        if ($request->ajax()) {
            return response()->json(['message' => 'Timelines created successfully', 'success' => true]);
        }
        return redirect()->route('project.index')->withSuccess("Timeline created");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $result = $this->timelineRepo->getById($id);
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTimelineRequest $request, string $id)
    {
       
            $result = $this->timelineRepo->updateById($id, $request->validated());

            if($request->ajax()){
                return response()->json(['message'=>'Updated', 'success'=>true]);
            }
            return redirect()->route('project.index')->withSuccess('Timeline updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
            $result = $this->timelineRepo->deleteById($id);
            return redirect()->route('project.index')->withSuccess('Timeline deleted successfully');
    }
}
