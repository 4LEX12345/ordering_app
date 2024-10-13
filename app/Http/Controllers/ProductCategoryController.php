<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Requests\ProductCategoryRequest;

class ProductCategoryController extends Controller
{
    public function index(){
        return view('masterdata.productcategory');
    }
    public function fetchData(){
        $product_categories = ProductCategory::all();
        return response()->json(['product_categories' => $product_categories], 200);
    }
    public function store(ProductCategoryRequest $request){
        $validated = $request->validated();
        ProductCategory::create($validated);
        return response()->json(['message' => 'Saved Succesfully!'], 200);
    }
    public function update(ProductCategoryRequest $request, $id){
        $validated = $request->validated();
        $product_category_to_update = ProductCategory::find($id);
        $product_category_to_update->update($validated);
        return response()->json(['message' => 'Updated Succesfully'], 200);
    }
    public function destroy($id){
        $product_category_to_delete = ProductCategory::find($id);
        $product_category_to_delete->delete();
        return response()->json(['message' => 'Deleted Succesfully'], 200);
    }

}
