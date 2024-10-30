<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemParameter;
use App\Http\Requests\SystemParameterRequest;

class SystemParameterController extends Controller
{
    public function index(){
        return view('system.systemparam');
    }
    public function fetchData(){
        $system_parameter = SystemParameter::first(); 
        return response()->json(['system_parameter' => $system_parameter], 200);
    }
    public function store(Request $request){
        
        $system_parameter = SystemParameter::first();
        $system_parameter->invoice_num = $request->invoice_num;
        $system_parameter->tracking_number = $request->tracking_number;
        $system_parameter->save();

        return response()->json(['message' => 'Saved Succesfully!'], 200);
    }
}
