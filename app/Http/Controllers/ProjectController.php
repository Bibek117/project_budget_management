<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repostories\ProjectRepository;
use App\Http\Requests\StoreProjectRequest;



class ProjectController extends Controller
{
    private  $projectRepo;
    public function __construct(ProjectRepository $projectRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->middleware('check_project',['only'=>['show','edit','update','destroy']]);
        $this->middleware('permission:view-project', ['only' => ['show']]);
        $this->middleware('permission:create-project|edit-project|delete-project', ['only' => ['index']]);
        $this->middleware('permission:create-project', ['only' => ['store', 'createProject']]);
        $this->middleware('permission:edit-project', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-project', ['only' => ['destroy']]);
        $this->middleware('permission:assign-user-to-project', ['only' => ['assignUserToProjectForm', 'assignUserToProject']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $result = $this->projectRepo->getAll();
            return view('projects.index', ['projects' => $result]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $req)
    {

        try {
            $result = $this->projectRepo->create($req->validated());
            return redirect()->route('project.index')->withSuccess('Project created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $project = $this->projectRepo->getById($id);
            return view('projects.show', ['project' => $project]);
        }catch(\Exception $e){
            return redirect()->back()->withErrors(['Error : '.$e->getMessage()]);
        }
    }

    //get project for ajax
    public function getSingleAjax(string $id)
    {
        try {
            $result = $this->projectRepo->getById($id);
            return response()->json(['project' => $result]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    //project edit form
    public function edit(String $id)
    {
        try {
            $project = Project::find($id);
            return view('projects.update', ['project' => $project]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProjectRequest $request, string $id)
    {
        try {
            $this->projectRepo->updateById($id, $request->validated());
            return redirect()->route('project.index')->withSuccess("Projected updated successfully");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->projectRepo->deleteById($id);
            return redirect()->route('project.index')->withSuccess("Projected deleted successfully");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }


    //create new project form
    public function create()
    {
        try {
            return view('projects.create');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }


    //assign projects to user form
    public function assignUserToProjectForm(String $id)
    {
        try {
            $asssignedUsers = DB::table('project_user')->where('project_id', $id)->pluck('user_id')->toArray();
            $project = $this->projectRepo->getById($id);
            return view('projects.assignUser', ['project' => $project, 'users' => User::without(['contact', 'record', 'projects'])->get(), 'assignedUsers' => $asssignedUsers]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
        
    }



    //assign projects to user store
    public function assignUserToProject(Request $request)
    {
        try {
            $validateReq = $request->validate([
                'user_id' => 'required|array',
                'project_id' => 'required'
            ]);
            $user = $this->projectRepo->getById($validateReq['project_id']);
            $user->users()->sync($validateReq['user_id']);

            return redirect()->route('project.index')->withSuccess("Users assigned to project successfully");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Error : ' . $e->getMessage()]);
        }
    }
}
