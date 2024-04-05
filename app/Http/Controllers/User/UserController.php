<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repostories\UserRepository;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userRepo;
    public function __construct(UserRepository $obj)
    {
        $this->userRepo = $obj;
    }
    //get all users
    public function index()
    {
        $res = User::paginate(5);
        return view('users.index', ['users' => $res]);
    }


    //create users form

    public function create()
    {
        return view('users.create');
    }

    //user edit
    public function edit(String $id){
        $user = User::find($id);
        return view('users.update',['user'=>$user]);
    }

    //update user details
    public function update(Request $request,String $id){
        $validateInput = $request->validate([
            'username'=>'required|string',
            'email'=>'required|email|unique:users,email,'.$id,
            'phone'=>'required'
        ]);

        $user = User::find($id);
        $user->update($validateInput);
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

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    //update TODO
    //login

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
}
