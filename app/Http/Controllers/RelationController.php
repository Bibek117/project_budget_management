<?php

namespace App\Http\Controllers;

//user assigned projects

use App\Models\User;
use App\Models\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelationController extends Controller
{
    //assign projects to user form
    public function assignProjectUserForm(String $id){
        $asssignedProjects = DB::table('project_user')->where('user_id',$id)->pluck('project_id')->toArray();
        $user = User::find($id);
        return view('users.assignProject',['user'=>$user,'projects'=>Project::without(['timeline'])->get(),'assignedProjects'=>$asssignedProjects]);
    }

    //assign projects to user
    public function assignProjectUser(Request $request){
        $validateReq = $request->validate([
            'user_id'=>'required',
            'project_id'=>'required|array'
        ]);
        $user = User::find($validateReq['user_id']);
        $user->projects()->sync($validateReq['project_id']);
        return redirect()->route('user.index')->withSuccess("Projects assigned to user successfully");
    }

    //assign projects to user form
    public function assignUserProjectForm(String $id)
    {
        $asssignedUsers = DB::table('project_user')->where('project_id', $id)->pluck('user_id')->toArray();
        $project = Project::find($id);
        return view('projects.assignUser', ['project' => $project, 'users' => User::without(['contact', 'record', 'projects'])->get(), 'assignedUsers' => $asssignedUsers]);
    }


    public function assignUserProject(Request $request)
    {
        $validateReq = $request->validate([
            'user_id' => 'required|array',
            'project_id' => 'required'
        ]);
        $user = Project::find($validateReq['project_id']);
        $user->users()->sync($validateReq['user_id']);

        return redirect()->route('project.index')->withSuccess("Users assigned to project successfully");
    }

    //detach project form user

    public function detachProjectUser(Request $request){
        $validateReq = $request->validate([
            'user_id' => 'required',
            'project_id' => 'required'
        ]);
        $user = User::find($validateReq['user_id']);
        $user->projects()->detach([$validateReq['project_id']]);

        return response()->json(['msg' => 'User assigned project successfully', 'success' => true]);
    }
}
