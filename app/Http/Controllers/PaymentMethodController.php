<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Requests\PaymentMethodRequest;

class PaymentMethodController extends Controller
{
    public function index(){
        return view('masterdata.paymentmethod');
    }
    public function fetchData(){
        $payment_methods = PaymentMethod::all(); 
        return response()->json(['payment_methods' => $payment_methods], 200);
    }
    public function store(PaymentMethodRequest $request){
        $validated = $request->validated();
        PaymentMethod::create($validated);
        return response()->json(['message' => 'Saved Succesfully!'], 200);
    }
    public function update(PaymentMethodRequest $request, $id){
        $validated = $request->validated();
        $payment_method_to_update =  PaymentMethod::find($id);
        $payment_method_to_update->update($validated);
        return response()->json(['message' => 'Updated Succesfully'], 200);
    }
    public function destroy($id){
        $payment_method_to_destroy = PaymentMethod::find($id);
        $payment_method_to_destroy->delete();
        return response()->json(['message' => 'Deleted Succesfully'], 200);
    }
}
