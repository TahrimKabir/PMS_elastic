<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        $username='elastic';
        $password='elastic';
        $data=["email"=>session('user')['email'],
    "post"=>$request->post];
        $url = 'http://localhost:9200/post/_doc';
        $createResponse = Http::withOptions(['verify' => false])
            ->withBasicAuth($username, $password)
            ->post($url, $data);

        // Return the response from Elasticsearch
        if ($createResponse->successful()) {
            return redirect()->back();
        } else {
            return response()->json([
                'message' => 'Failed to create user in Elasticsearch',
                'error' => $createResponse->json()
            ], $createResponse->status());
        }
        
    }
}
