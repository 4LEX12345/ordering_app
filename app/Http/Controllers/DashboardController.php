<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\OrderHeader;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        $order_per_month = OrderHeader::selectRaw('MONTH(order_date) as month, COUNT(id) as total_orders')
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->get();

        $collection_month = [];

        for($i = 1; $i <= 12; $i++ ){
            $has_month = $order_per_month->where('month', $i)->first();

            if($has_month != null){
                $collection_month[] =   $has_month->total_orders;
            }
            else{
                $collection_month[] = 0;
            }
        }
        return view('system.dashboard', compact('collection_month'));
    }

    public function fetchData(){
        $orders = OrderHeader::all();

        $top_products = OrderDetail::selectRaw('COUNT(product_id) as product_count,product_id')
        ->groupBy('product_id')
        ->limit(5)
        ->get()
        ->map(function($item) {
            $item->product_name = $item->product->name;
            $item->product_image = $item->product->image;
            $item->brand = $item->product->brand->name;
            $item->flavor = $item->product->flavor->name;
            return $item;
        });


        return response()->json([
            'orders' => $orders,
            'top_products' => $top_products,
        ]);
    }
}
