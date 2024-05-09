<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Contacttype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repostories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userRepo;
    public function __construct(UserRepository $obj)
    {
        $this->userRepo = $obj;
        $this->middleware('permission:delete-user|register-user|view-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:register-user', ['only' => ['create', 'register']]);
        $this->middleware('permission:assign-project-to-user', ['only' => ['assignProjectToUserForm', 'assignProjectToUser']]);
        $this->middleware('permission:assign-contact', ['only' => ['assignContactTypeToUserForm', 'assignContactTypeToUser']]);
    }
    //get all users
    public function index()
    {
        $res = DB::select('SELECT u.*,r.name FROM users AS u LEFT JOIN model_has_roles AS mr ON mr.model_id = u.id INNER JOIN roles AS r ON r.id = mr.role_id where r.name NOT IN ("Admin","Super Admin")');
        return view('users.index', ['users' => $res]);
    }


    //create users form

    public function create()
    {
        return view('users.create');
    }

    //user edit
    public function edit(Request $req, String $id)
    {
        try {
            $assignedContacts = DB::table('contacts')->where('user_id', $id)->pluck('contacttype_id')->toArray();
            $asssignedProjects = DB::table('project_user')->where('user_id', $id)->pluck('project_id')->toArray();
            $user = $this->userRepo->getById($id);
            return response()->json(['user' => $user, 'contacttypes' => Contacttype::get(), 'assignedProjects' => $asssignedProjects, 'assignedContacts' => $assignedContacts, 'projects' => Project::without(['timeline'])->get()]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'success' => false]);
        }
    }

    //update user details
    // $id =  userId

    public function update(UpdateUserRequest $request, String $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $userDetails = $request->only(['username', 'email', 'phone']);

                if ($request->has('contacttype_id')) {
                    $this->userRepo->assignContactTypeToUser([
                        'user_id' => $id,
                        'contacttype_id' => $request->input('contacttype_id'),
                    ]);
                }

                if ($request->has('project_id')) {
                    $this->userRepo->assignProjectToUser([
                        'user_id' => $id,
                        'project_id' => $request->input('project_id'),
                    ]);
                }

                $this->userRepo->updateById($id, $userDetails);
            });

            return response()->json(['message' => 'User details updated successfully']);
        } catch (\Exception $error) {
            return response()->json(['message' => 'User update failed' . $error->getMessage()]);
        }
    }




    //register 
    public function register(StoreUserRequest $request)
    {
        try {
            $validatedInput = $request->validated();
            //hashing password 
            $validatedInput['password'] = Hash::make($validatedInput['password']);
            $res = $this->userRepo->create($validatedInput);
            return redirect()->route('user.index')->withSuccess('New user registered successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'User registration failed: ' . $e->getMessage()]);
        }
    }

    //login form
    public function login(){
        return view('auth.login');
    }
    //login
    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('dashboard')->withSuccess('Welcome back!!');
            }
            return back()->withErrors([
                'email' => 'Credentials do not match!! Please try again'
            ])->onlyInput('email');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Authentication failed: ' . $e->getMessage()]);
        }
    }

    //logout
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();

            //to invalidate csrf token if any how obtained by hacker
            $request->session()->regenerateToken();

            return redirect()->route('login');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Logout failed: ' . $e->getMessage()]);
        }
    }

    //getById
    public function show(String $id)
    {
        try {
            $res = $this->userRepo->getById($id);
            return response()->json(['result' => $res, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e->getMessage(), 'success' => false]);
        }
    }

    //delete 
    public function destroy(String $id)
    {
        try {
           $this->userRepo->deleteById($id);
            return redirect()->back()->withSuccess("User deleted successfully");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'User deletion failed: ' . $e->getMessage()]);
        }
    }

    //get users given contact type
    public function getUsersAjax(string $id)
    {
        try {
            $result = DB::select('select users.id,users.username from users inner join contacts on users.id = contacts.user_id where contacts.contacttype_id = ?', [$id]);
            return response()->json(['users' => $result]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching users: ' . $e->getMessage()]);
        }
    }

    //assign project/s to user

    // public function assignProjectToUserForm(String $id)
    // {
    //     $asssignedProjects = DB::table('project_user')->where('user_id', $id)->pluck('project_id')->toArray();
    //     $user = $this->userRepo->getById($id);
    //     return view('users.assignProject', ['user' => $user, 'projects' => Project::without(['timeline'])->get(), 'assignedProjects' => $asssignedProjects]);
    // }


    //assign projects to user
    //   public function assignProjectToUser(Request $request )
    // public function assignProjectToUser(array $request)
    // {
    //     $user = $this->userRepo->getById($request['user_id']);
    //     $user->projects()->sync($request['project_id']);

    //     // return redirect()->route('user.index')->withSuccess("Projects assigned to user successfully");
    // }


    //assign contact type to user 
    // public function assignContactTypeToUserForm(String $id)
    // {
    //     $assignedContatcs = DB::table('contacts')->where('user_id', $id)->pluck('contacttype_id')->toArray();
    //     $user = $this->userRepo->getById($id);
    //     return view('users.assignContacttype', ['user' => $user, 'assignedContacts' => $assignedContatcs, 'contacttypes' => Contacttype::get()]);
    // }

    //assign contact type to user ->store
    // public function assignContactTypeToUser(Request $request)
    // public function assignContactTypeToUser(array $request)
    // {
    //     $assignedContatcIds = DB::table('contacts')->where('user_id', $request['user_id'])->pluck('contacttype_id')->toArray();

    //     $newIdsToAssign = $request['contacttype_id'];

    //     //arrar_diff returns array of difference in elements
    //     $IDsToAttach = array_diff($newIdsToAssign, $assignedContatcIds);
    //     $IDsToDettach = array_diff($assignedContatcIds, $newIdsToAssign);


    //     $data = [];
    //     foreach ($IDsToAttach as $contacttypeId) {
    //         $data[] = [
    //             'user_id' => $request['user_id'],
    //             'contacttype_id' => $contacttypeId,
    //         ];
    //     }
    //     Contact::insert($data);

    //     Contact::where('user_id', $request['user_id'])
    //         ->whereIn('contacttype_id', $IDsToDettach)
    //         ->delete();

    // return redirect()->route('user.index')->withSuccess("Contact Types assigned successfully");
    // }




    // public function one()
    // {
    //     $this->two(null);
    //     return "test";
    // }

    // public function two($a)
    // {
    //     try {
    //         if (in_array(null, (array)$a, true)) {
    //             throw new \Exception('Array contains null value');
    //         }
    //         return false;
    //     } catch (\Exception $e) {
    //         dd($e);
    //     }
    // }

}
