<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Thread;


class UserController extends Controller
{

    public function register(Request $request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:dns',
            'password' => 'required',
        ],[
            'first_name.required' => 'First Name is required',
            'last_name.required' => 'Last Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'password.required' => 'Password is required',

        ]);

        $createData = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        if($createData){
            return redirect()->route('login.form')->with('success', 'Registration Successful. Please Login.');
        }else{
            return redirect()->back()->with('error', 'Registration Failed. Please try again.');}
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ],[
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'password.required' => 'Password is required',

        ]);

        $data = $request->only('email', 'password');
        if(Auth::attempt($data)){
            if(Auth::user()->role == 'admin'){
                return redirect()->route('admin.dashboard')->with('success', 'Login Successful. Welcome Admin.');
                }
                return redirect()->route('index')->with('success', 'Login Successful.');
            }else{
                return redirect()->back()->with('error', 'Login Failed. Please try again.');
            }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('index')->with('success', 'Logout Successful.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $userId = Auth::id();

    $threads = Thread::where('user_id', $userId)
        ->where('status', 'published')
        ->latest()
        ->get();

    $drafts = Thread::where('user_id', $userId)
        ->where('status', 'draft')
        ->latest()
        ->get();

    return view('account', compact('threads', 'drafts'));
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function updateProfilePicture(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'You must be logged in.');
        }

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $file = $request->file('profile_picture');
        $fileName = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile_pictures', $fileName, 'public');

        User::where('id', Auth::id())->update(['profile_picture' => $path]);

        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
