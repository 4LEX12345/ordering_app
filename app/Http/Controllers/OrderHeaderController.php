<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SystemParameter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderHeaderController extends Controller
{
    public function index(){
        return view('order');
    }
    public function fetchData(){
        $system_param =  SystemParameter::first();
        $tracking_number = $system_param->tracking_number;
        $date_today = Carbon::today()->format('Y-m-d');
        $products = Product::get()
        ->map(function($item){
            $item->flavor_name = $item->flavor->name;
            $item->brand_name = $item->brand->name;
            $item->product_category_name = $item->productCategory->name;
            $item->input_quantity = 0;
            $item->unit_price = 0;
            $item->total = 0;
            $item->from_db = false;
            return $item;
        });
        return response()->json(['date_today' => $date_today, 'tracking_number' => $tracking_number, 'products' => $products], 200);
    }
}
