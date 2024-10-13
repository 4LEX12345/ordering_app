<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Flavor;
use App\Models\Brand;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index(){
        return view('product');
    }
    public function fetchData(){
        $products = Product::with(['flavor','brand','productCategory'])->orderBy('id','DESC')->get();
        $brands = Brand::all(); 
        $flavors = Flavor::all();
        $product_categories = ProductCategory::all();

        return response()->json(
        [
            'products' => $products,
            'brands' => $brands,
            'product_categories' => $product_categories,
            'flavors' => $flavors,
        ]);
    }
    public function store(ProductRequest $request){

        //validation of request  App\Http\Requests\ProductRequest
        $validated = $request->validated();
        $path = null;

        if($request->file('image')){
            $path = $request->file('image')->store('images', 'public'); //store the image on public folder
        }

        $store_product = new Product();
        $store_product->name = $validated['name'];
        $store_product->description = $validated['description'];
        $store_product->brand_id = $validated['brand_id'];
        $store_product->product_category_id = $validated['product_category_id'];
        $store_product->flavor_id = $validated['flavor_id'];
        $store_product->image = $path;
        $store_product->save();
        return response()->json(['message' => 'Saved Succesfully!'], 200);
    }

    public function update(ProductRequest $request){
        
        //validation of request  App\Http\Requests\ProductRequest
        $validated = $request->validated();
        $path = null;

        $update_product = Product::find($request->id);
        if($request->file('image')){
            if($update_product->image != null){
                $this->deletePreviousImage($update_product->image);
            }
            $path = $request->file('image')->store('images', 'public'); //store the image on public folder
            $update_product->image = $path;
        }
        $update_product->name = $validated['name'];
        $update_product->description = $validated['description'];
        $update_product->brand_id = $validated['brand_id'];
        $update_product->product_category_id = $validated['product_category_id'];
        $update_product->flavor_id = $validated['flavor_id'];
        $update_product->save();

        return response()->json(['message' => 'Updated Succesfully!'], 200);
    }

    public function destroy($id){
        $product_to_delete = Product::find($id);
        $this->deletePreviousImage($product_to_delete->image);
        $product_to_delete->delete();
        return response()->json(['message' => 'Deleted Succesfully'], 200);
    }

    function deletePreviousImage($url){
         // Check if the file exists in the 'public' disk
        if (\Storage::disk('public')->exists($url)) {
            // Delete the file
            \Storage::disk('public')->delete($url);
        }
    }
}
