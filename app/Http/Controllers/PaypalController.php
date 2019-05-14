<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaypalController extends Controller
{
    public function index(Request $request){
        return response()->json(['apple'=>'red','peach'=>'pink']);
    }
}