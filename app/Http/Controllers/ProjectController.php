<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repostories\ProjectRepository;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;


class ProjectController extends Controller
{
    private  $projectRepo;
    public function __construct(ProjectRepository $projectRepo)
    {
        $this->projectRepo = $projectRepo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->projectRepo->getAll();
        return view('projects.index', ['projects' => $result]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $req)
    {
        $result = $this->projectRepo->create($req->validated());
        return redirect()->route('project.index')->withSuccess('Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = $this->projectRepo->getById($id);
        return view('projects.show', ['project' => $result]);
    }

    //get project for ajax
    public function getSingleAjax(string $id)
    {
        $result = $this->projectRepo->getById($id);
        return response()->json(['project' => $result]);
    }

    //project edit form
    public function edit(String $id)
    {
        $project = Project::find($id);
        return view('projects.update', ['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProjectRequest $request, string $id)
    {
        $result = $this->projectRepo->updateById($id, $request->validated());
        return redirect()->route('project.index')->withSuccess("Projected updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->projectRepo->deleteById($id);
        return redirect()->route('project.index')->withSuccess("Projected deleted successfully");
    }


    //create new project form
    public function createProject()
    {
        return view('projects.create');
    }
}
