<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(){
        return view('masterdata.role');
    }
    public function fetchData(){
        $roles = Role::all(); 
        return response()->json(['roles' => $roles], 200);
    }
    public function store(RoleRequest $request){
        $validated = $request->validated();
        Role::create($validated);
        return response()->json(['message' => 'Saved Succesfully!'], 200);
    }
    public function update(RoleRequest $request, $id){
        $validated = $request->validated();
        $role_to_update =  Role::find($id);
        $role_to_update->update($validated);
        return response()->json(['message' => 'Updated Succesfully'], 200);
    }
    public function destroy($id){
        $role_to_destroy = Role::find($id);
        $role_to_destroy->delete();
        return response()->json(['message' => 'Deleted Succesfully'], 200);
    }
    
}
