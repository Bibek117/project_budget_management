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
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userRepo;
    public function __construct(UserRepository $obj)
    {
        $this->userRepo = $obj;
        $this->middleware('permission:delete-user|register-user|view-user',['only'=>['index','show']]);
        $this->middleware('permission:register-user',['only'=>['create','register']]);
        $this->middleware('permission:assign-project-to-user',['only'=>['assignProjectToUserForm', 'assignProjectToUser']]);
        $this->middleware('permission:assign-contact',['only'=>['assignContactTypeToUserForm', 'assignContactTypeToUser']]);
    }
    //get all users
    public function index()
    {
        $res = User::paginate(3);
        return view('users.index', ['users' => $res]);
    }


    //create users form

    public function create()
    {
        return view('users.create');
    }

    //user edit
    public function edit(Request $req,String $id){
        $assignedContacts = DB::table('contacts')->where('user_id', $id)->pluck('contacttype_id')->toArray();
        $asssignedProjects = DB::table('project_user')->where('user_id', $id)->pluck('project_id')->toArray();
        $user = $this->userRepo->getById($id);
        if($req->ajax()){
            return response()->json(['user'=>$user, 'contacttypes' => Contacttype::get(),'assignedProjects'=>$asssignedProjects,'assignedContacts'=>$assignedContacts, 'projects' => Project::without(['timeline'])->get()]);
        }
        return view('users.update',['user'=>$user]);
    }

    //update user details
     // $id =  userId
    public function update(Request $request,String $id){
        //dd($request);
        $validatedInput = $request->validate([
            'username'=>'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone'=>'required',
             'contacttype_id'=>'array',
             'project_id'=>'array'
        ]);
       
        $userDetails = [
            'username'=>$validatedInput['username'],
            'email'=>$validatedInput['email'],
            'phone'=>$validatedInput['phone'],
        ];

        if($validatedInput['contacttype_id']??false){
            $userContact = [
                'user_id'=>$id,
                'contacttype_id'=> $validatedInput['contacttype_id'],
            ];
           $this->assignContactTypeToUser($userContact);
        }

        if($validatedInput['project_id']??false){
            $userProject = [
                'user_id'=>$id,
                'project_id'=> $validatedInput['project_id'],
            ];

            $this->assignProjectToUser($userProject);
        }

        $user = $this->userRepo->getById($id);
        $user->update($userDetails);

        if ($request->ajax()) {
            return response()->json(['message' => 'User details updated successfully']);
        }

        return redirect()->route('user.index')->withSuccess('User updated successfully');
    }



    //register 
    public function register(StoreUserRequest $request)
    {
        $validatedInput = $request->validated();
        //hashing password 
        $validatedInput['password'] = Hash::make($validatedInput['password']);
        $res = $this->userRepo->create($validatedInput);
        return redirect()->route('user.index')->withSuccess('New user registered successfully');
    }

    //show login form
    public function login()
    {
        return view('auth.login');
    }


    //login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashbaord');
        };
        return back()->withErrors([
            'email' => 'Credentials do not match!! Please try again'
        ])->onlyInput('email');
    }

    //logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        //to invalidate csrf token if any how obtained by hacker
        $request->session()->regenerateToken();

        return redirect()->route('login');
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
        $res = $this->userRepo->deleteById($id);
        return redirect()->back()->withSuccess("User deleted successfully");
    }



    //assign project/s to user

    public function assignProjectToUserForm(String $id)
    {
        $asssignedProjects = DB::table('project_user')->where('user_id', $id)->pluck('project_id')->toArray();
        $user = $this->userRepo->getById($id);
        return view('users.assignProject', ['user' => $user, 'projects' => Project::without(['timeline'])->get(), 'assignedProjects' => $asssignedProjects]);
    }


     //assign projects to user
    //   public function assignProjectToUser(Request $request )
    public function assignProjectToUser(array $request )
    {
        $user = $this->userRepo->getById($request['user_id']);
        $user->projects()->sync($request['project_id']);

        // return redirect()->route('user.index')->withSuccess("Projects assigned to user successfully");
    }


    //assign contact type to user 
    public function assignContactTypeToUserForm(String $id)
    {
        $assignedContatcs = DB::table('contacts')->where('user_id', $id)->pluck('contacttype_id')->toArray();
        $user = $this->userRepo->getById($id);
        return view('users.assignContacttype', ['user' => $user, 'assignedContacts' => $assignedContatcs, 'contacttypes' => Contacttype::get()]);
    }

    //assign contact type to user ->store
    // public function assignContactTypeToUser(Request $request)
    public function assignContactTypeToUser(array $request)
    {
        $assignedContatcIds = DB::table('contacts')->where('user_id', $request['user_id'])->pluck('contacttype_id')->toArray();

        $newIdsToAssign = $request['contacttype_id'];

        //arrar_diff returns array of difference in elements
        $IDsToAttach = array_diff($newIdsToAssign, $assignedContatcIds);
        $IDsToDettach = array_diff($assignedContatcIds,$newIdsToAssign);


        $data = [];
        foreach ($IDsToAttach as $contacttypeId) {
            $data[] = [
                'user_id' => $request['user_id'],
                'contacttype_id' => $contacttypeId,
            ];
        }
        Contact::insert($data);

        Contact::where('user_id',$request['user_id'])
                 ->whereIn('contacttype_id',$IDsToDettach)
                 ->delete();

        // return redirect()->route('user.index')->withSuccess("Contact Types assigned successfully");
    }
}

