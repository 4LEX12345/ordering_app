<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(){
        return view('masterdata.brand');
    }
    public function fetchData(){
        $brands = Brand::all(); 
        return response()->json(['brands' => $brands], 200);
    }
    public function store(BrandRequest $request){
        $validated = $request->validated();
        Brand::create($validated);
        return response()->json(['message' => 'Saved Succesfully!'], 200);
    }
    public function update(BrandRequest $request, $id){
        $validated = $request->validated();
        $brand_to_update =  Brand::find($id);
        $brand_to_update->update($validated);
        return response()->json(['message' => 'Updated Succesfully'], 200);
    }
    public function destroy($id){
        $brand_to_destroy = Brand::find($id);
        $brand_to_destroy->delete();
        return response()->json(['message' => 'Deleted Succesfully'], 200);
    }
}
