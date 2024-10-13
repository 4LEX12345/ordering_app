<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flavor;
use App\Http\Requests\FlavorRequest;


class FlavorController extends Controller
{
    public function index(){
        return view('masterdata.flavor');
    }
    public function fetchData(){
        $flavors = Flavor::all();
        return response()->json(['flavors' => $flavors], 200);
    }
    public function store(FlavorRequest $request){

        $validated = $request->validated();
        Flavor::create($validated);
        return response()->json(['message' => 'Saved Succesfully!'], 200);
    }
    public function update(FlavorRequest $request, $id){
        $validated = $request->validated();
        $flavor_to_update = Flavor::find($id);
        $flavor_to_update->update($validated);
        return response()->json(['message' => 'Updated Succesfully'], 200);
    }
    public function destroy($id){
        $flavor_to_destroy = Flavor::find($id);
        $flavor_to_destroy->delete();
        return response()->json(['message' => 'Deleted Succesfully'], 200);
    }
}
