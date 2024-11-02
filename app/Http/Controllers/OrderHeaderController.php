<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\OrderHeader;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use App\Models\PaymentHistory;
use App\Models\SystemParameter;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\OrderHeaderRequest;
use App\Models\PaymentMethod;

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
            $item->total = 0;
            $item->from_db = false;
            return $item;
        });
        $orders = OrderHeader::get()
        ->map(function($item){
            $item->business_name = $item->customer->business_name;
            $item->contact_person = $item->customer->contact_person;
            $item->contact_person_contact_number = $item->customer->contact_person_contact_number;
            $item->contact_person_email = $item->customer->contact_person_email;
            $item->billing_address = $item->customer->billing_address;
            $item->shipping_address = $item->customer->shipping_address;
            $item->user_id = $item->user->id;
            $item->user_name = $item->user->name;
            return $item;
        });

        $payment_methods = PaymentMethod::all();

        return response()->json([
            'date_today' => $date_today, 
            'tracking_number' => $tracking_number, 
            'products' => $products, 
            'orders' => $orders,
            'payment_methods' => $payment_methods,
        ], 200);
    }
    public function store(OrderRequest $request){
        DB::beginTransaction();
        try{
            $validated = $request->validated();
            $system_param  = SystemParameter::first();
            $date_today = Carbon::today()->format('Y-m-d');
            $total_payment = 0;
            $path = null;
            if($request->file('image')){
                $path = $request->file('image')->store('images', 'public'); //store the image on public folder
            }
            $customer = new Customer();
            $customer->business_tax_id = $path; //image path
            $customer->business_name = $request->business_name;
            $customer->contact_person = $request->contact_person;
            $customer->contact_person_contact_number = $request->contact_person_contact_number;
            $customer->contact_person_email = $request->contact_person_email;
            $customer->billing_address = $request->billing_address;
            $customer->shipping_address = $request->shipping_address;
            $customer->wholesale_account_number  = 'TEST-001';
            $customer->save();
            if($customer){
                //store the payment and order information
                $order_header = new OrderHeader();
                $order_header->customer_id  = $customer->id;
                $order_header->order_date  = $validated['order_date'];
                $order_header->payment_method  = $validated['payment_method'];
                $order_header->tracking_number  = $validated['tracking_number'];
                $order_header->total_amount  = $request->sub_total;
                $order_header->payment_status  = 'pending';
                $order_header->delivery_status  = 'pending';
                $order_header->invoice_num = $system_param->invoice_num;
                $order_header->user_id = Auth::user()->id;
                $order_header->save();
                if($order_header){
                    //save the payment if it has value or != 0
                    if($request->payment != 0){
                        $payment = new PaymentHistory();
                        $payment->order_header_id = $order_header->id;
                        $payment->amount = $request->payment;
                        $payment->payment_date = $date_today;
                        $payment->payment_method =  $validated['payment_method'];
                        $payment->save();

                        if($payment){
                            $total_payment += $payment->amount;
                        }
                    }
                    //store the items selected
                    foreach($request->items as $item){
                        $item_iteration = json_decode($item, true);
                        $order_detail = new OrderDetail();
                        $order_detail->order_header_id = $order_header->id;
                        $order_detail->product_id = $item_iteration['id'];
                        $order_detail->quantity = $item_iteration['input_quantity'];
                        $order_detail->unit_price = $item_iteration['unit_price'];
                        $order_detail->save();
                    }
                    //update the system parameter e.g invoice_num and tracking_num
                    $this->updateSystemParam(); //increment the values
                }
                //update the payment status if fully paid  
                $is_paid = $total_payment == $order_header->total_amount ;
                $update_order_status = OrderHeader::find($order_header->id);
                $update_order_status->payment_status =  $is_paid ? 'paid' : 'pending';
                $update_order_status->save();
            }
            DB::commit();
            return response()->json([
                'message' => 'success', 
                'order_id' => $order_header->id,
                'is_paid' => $is_paid
            ],200);
        }
        catch(\Exception $e){
            //rollback if there is an error saving all the data 
            DB::rollback();
            //delete the image stored if there is 
            if($path){
                Storage::disk('public')->delete($path);
            }
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function edit($id){
        $data = OrderHeader::with(['customer', 'orderDetails', 'payments'])->find($id);
        $payments = PaymentHistory::where('order_header_id', $data->id)
        ->get()
        ->map(function($item) {
            $item->payment_method_name = $item->paymentMethod->name;
            return $item;
        });
        $order_details = OrderDetail::where('order_header_id', $data->id)
        ->get()
        ->map(function($item){
            $item->product_name = $item->product->name;
            $item->total= $item->quantity * $item->unit_price;
            return $item;
        });
        return response()->json([
            'data' => $data, 
            'order_details' => $order_details,
            'payments' => $payments,
        ], 200);
    }
    public function update(OrderHeaderRequest $request, $id){

        $validated = $request->validated();

        $payment = new PaymentHistory();
        $payment->order_header_id  = $id;
        $payment->amount   = $validated['amount'];
        $payment->payment_date  =$validated['payment_date'];
        $payment->payment_method  =$validated['payment_method'];
        $payment->save();   

        $data = OrderHeader::with(['customer', 'orderDetails', 'payments'])->find($id);
        $payments = PaymentHistory::where('order_header_id', $data->id)
        ->get()
        ->map(function($item) {
            $item->payment_method_name = $item->paymentMethod->name;
            return $item;
        });
        $order_details = OrderDetail::where('order_header_id', $data->id)
        ->get()
        ->map(function($item){
            $item->product_name = $item->product->name;
            $item->total= $item->quantity * $item->unit_price;  
            return $item;
        });

        return response()->json([
            'message' => 'Payment Updated!',
            'data' => $data, 
            'order_details' => $order_details,
            'payments' => $payments, 
        ],200);
    }
    public function generateInvoice($id){
        $order = OrderHeader::findOrFail($id);
         //store all the order information into session
        session()->put('order', $order);
        return response()->json(['message'=> 'success'], 200);
    }
    public function generateFinalInvoice($id){
        //update the order into paid and delivered
        $order = OrderHeader::findOrFail($id);
        $order->payment_status = 'paid';
        $order->delivery_status = 'delivered';
        $order->save();
        //store all the order information into session
        session()->put('order', $order);
        return response()->json(['message'=> 'success'], 200);
    }
    public function showInvoice(){
        //retrieve the order information into the session from generateInvoice() function
        $order = session()->get('order');
        return view('invoice.invoice', compact('order'));
    }
    public function showFinalInvoice(){
        //retrieve the order information into the session from generateFinalInvoice() function
        $order = session()->get('order');
        return view('invoice.finalinvoice', compact('order'));
    }
    function updateSystemParam(){
        $system_param  = SystemParameter::first();
        $system_param->invoice_num = str_pad($system_param->invoice_num + 1, 3, '0', STR_PAD_LEFT);
        $system_param->tracking_number = str_pad($system_param->tracking_number + 1, 3, '0', STR_PAD_LEFT);
        $system_param->save();
    }
}
