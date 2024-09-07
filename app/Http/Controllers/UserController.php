<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule; 
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $roles = Role::get();
        return view('system.user', compact('roles'));
    }
    public function getData(){
        $users = User::with('roles')->get();
        return response()->json(['users' => $users], 200);
    }
    public function store(Request $request){
        
        $isUpdate = isset($request->id);

        $rule = [
            'name' => 'required',
            'role_id' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->id), // Ignore the current user ID during update
            ],
            'password' => $isUpdate ? 'nullable' : 'required|min:8|confirmed', 
        ];  
        $validated = $request->validate(
        $rule, 
        [
            'name.required' => 'Full Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'email.unique' => 'Email is already used.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'role_id.required' => 'User Role is required.',
        ]);

        if(isset($request->id)){
            $user = User::find($request->id);
            $user->roles()->sync($validated['role_id']);
        }else{
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
           ]);
    
           $user->roles()->attach($validated['role_id']);
        }
        return response()->json(['message' => 'New User Created!'], 200);
    }
    public function destroy($id){
        $destroy = User::where('id', $id)->delete();

        if($destroy){
            return response()->json(['message' => 'User Succesfully Deleted!'], 200);
        }
    }   
    public function changePassword(Request $request){

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data =User::where('id', $request->id)->first();
        $data->password = bcrypt($request->password);
        $data->save();

        return response()->json(['message' => 'Password Updated!'], 200);
    }
}
