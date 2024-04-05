<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\MOdels\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-role|edit-role|delete_role',['only'=>['index','show']]);
        $this->middleware('permission:create-role',['only'=>'store','create']);
        $this->middleware('permission:edit-role',['only'=>'edit','update']);
        $this->middleware('permission:delete-role',['only'=>['destroy']]);
        $this->middleware('permission:assign-role',['only'=>['assign', 'editAddAssignedRole','updateAssignedRoles']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('role.show', ['roles' => Role::orderBy('id')->paginate(5)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role.create', ['permissions' => Permission::get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        DB::transaction(function () use ($validatedData) {

            $role = Role::create(['name' => $validatedData['name']]);
            $permissions = DB::table('permissions')->whereIn('id', $validatedData['permissions'])->pluck('name');
            // dd($permissions);
            $role->syncPermissions($permissions);
        });
        return redirect()->route('roles.index')->withSuccess('New role added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $permissions = DB::select('select permissions.name from permissions inner join role_has_permissions on permissions.id = role_has_permissions.permission_id where role_has_permissions.role_id = ?', [$role->id]);
        return view('role.singleRole', ['role' => $role, 'permissions' => $permissions]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $roleAssignedPermissions =  DB::table('role_has_permissions')->where('role_id', $role->id)->pluck('permission_id')->all();
        return view('role.update', ['role' => $role, 'permissions' => Permission::get(), 'roleAssignedPermissions' => $roleAssignedPermissions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'permissions' => 'required'
        ]);
        $role->update(['name' => $validatedData['name']]);
        $permissions = DB::table('permissions')->whereIn('id', $validatedData['permissions'])->pluck('name');
        $role->syncPermissions($permissions);
        return redirect()->route('roles.index')->withSuccess('Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->name == "Super Admin") {
            abort(403, "Cannot delete super admin");
        }
        $role->delete();
        return redirect()->back()->withSuccess("Role deleted successfully");
    }

    //show assigned roles with option to edit
    public function assign()
    {
        $userAssociatedRoles = DB::select('SELECT users.id,users.username, GROUP_CONCAT(roles.name) AS roles, GROUP_CONCAT(roles.id) AS role_ids
     FROM users
     INNER JOIN model_has_roles rel ON rel.model_id = users.id
     INNER JOIN roles ON roles.id = rel.role_id
     GROUP BY users.username,users.id;');
        return view('role.assignRole', ['users' => User::all(), 'roles' => Role::all(), 'userAssociatedRoles' => $userAssociatedRoles]);
    }

    //assign role or edit form

    public function editAddAssignedRole(User $user){
        $assignedRoles = DB::table('model_has_roles')->where('model_id',$user->id)->pluck('role_id')->all();
        return view('role.updateAssignedRole',['assignedRoles'=>$assignedRoles,'roles'=>Role::get(),'user'=>$user]);
    }

    //update assigned roles to db
    public function updateAssignedRoles(){

    }

}