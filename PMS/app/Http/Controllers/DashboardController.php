<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        if(session()->has('user')){

            return view('dashboard');
        }else{
            return redirect('/login');
        }
        
    }

    public function post(Request $request){
        return redirect()->back();
    }
}
