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
        $this->middleware('permission:create-role|edit-role|delete-role|view-role', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-role', ['only' => ['store', 'create']]);
        $this->middleware('permission:edit-role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
        $this->middleware('permission:assign-role', ['only' => ['assign', 'editAddAssignedRole', 'updateAssignedRoles']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('role.show', ['roles' => Role::where('name', '!=', 'Super Admin')->orderBy('id')->paginate(5)]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error fetching roles: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('role.create', ['permissions' => Permission::get()]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error fetching permissions: ' . $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:roles,name',
                'permissions' => 'required|array'
            ]);

            DB::beginTransaction();

            $role = Role::create(['name' => $validatedData['name']]);
            $permissions = DB::table('permissions')->whereIn('id', $validatedData['permissions'])->pluck('name');
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()->route('roles.index')->withSuccess('New role added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => 'Role creation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        try {
            if ($role->name == "Super Admin") {
                return abort(403);
            }
            $roleAssignedPermissions = DB::table('role_has_permissions')->where('role_id', $role->id)->pluck('permission_id')->all();
            return view('role.update', ['role' => $role, 'permissions' => Permission::get(), 'roleAssignedPermissions' => $roleAssignedPermissions]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error editing role: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        try {
            if ($role->name == "Super Admin") {
                return abort(403);
            }
            $roleAssignedPermissions = DB::table('role_has_permissions')->where('role_id', $role->id)->pluck('permission_id')->all();
            return view('role.update', ['role' => $role, 'permissions' => Permission::get(), 'roleAssignedPermissions' => $roleAssignedPermissions]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error editing role: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            if ($role->name == "Super Admin") {
                return abort(403);
            }
            $validatedData = $request->validate([
                'name' => 'required|string',
                'permissions' => 'required'
            ]);

            DB::beginTransaction();

            $role->update(['name' => $validatedData['name']]);
            $permissions = DB::table('permissions')->whereIn('id', $validatedData['permissions'])->pluck('name');
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()->route('roles.index')->withSuccess('Role updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => 'Role update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            if ($role->name == "Super Admin") {
                abort(403, "Cannot delete super admin");
            }
            $role->delete();
            return redirect()->back()->withSuccess("Role deleted successfully");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Role deletion failed: ' . $e->getMessage()]);
        }
    }

    //show assigned roles with option to edit
    public function assign()
    {
        try {
            $userAssociatedRoles = DB::select('SELECT users.id,users.username, GROUP_CONCAT(roles.name) AS roles, GROUP_CONCAT(roles.id) AS role_ids
                               FROM users
                               LEFT JOIN model_has_roles rel ON rel.model_id = users.id
                               LEFT JOIN roles ON roles.id = rel.role_id
                               WHERE roles.name != "Super Admin" OR roles.name IS NULL
                               GROUP BY users.username,users.id;');
            return view('role.assignRole', ['userAssociatedRoles' => $userAssociatedRoles]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error fetching user roles: ' . $e->getMessage()]);
        }
    }

    //assign role or edit form

    public function editAddAssignedRole($id)
    {
        try {
            $user = User::find($id);
            $assignedRoles = DB::table('model_has_roles')->where('model_id', $user->id)->pluck('role_id')->toArray();
            return view('role.updateAssignedRole', ['assignedRoles' => $assignedRoles, 'roles' => Role::where('name', '!=', 'Super Admin')->get(), 'user' => $user]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Error editing assigned roles: ' . $e->getMessage()]);
        }
    }

    //update assigned roles to db
    public function updateAssignedRoles(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required',
                'roles' => 'required|array'
            ]);

            DB::beginTransaction();

            $user = User::find($validatedData['user_id']);
            $selectedRoleNames = DB::table('roles')->whereIn('id', $validatedData['roles'])->pluck('name');
            $user->syncRoles($selectedRoleNames);

            DB::commit();

            return redirect()->route('roles.assign')->withSuccess("Roles assigned");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => 'Error updating assigned roles: ' . $e->getMessage()]);
        }
    }
}
