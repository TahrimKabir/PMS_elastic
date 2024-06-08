<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
class RegisterController extends Controller
{
    public function index(){
        return view('index');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email',
            'pass1' => 'required|string',
            'pass2' => 'required|string|same:pass1',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validated();

        // Combine fname and lname into a single name field
        $data = [
            'name' => $validated['fname'] . ' ' . $validated['lname'],
            'email' => $validated['email'],
            'password' => $validated['pass1'],
        ];

        // Define the Elasticsearch endpoint and credentials
        $url = 'https://localhost:9200/users/_doc';
        $username = 'elastic';
        $password = 'elastic';

        // Send the POST request to Elasticsearch
        $response = Http::withOptions(['verify' => false])->withBasicAuth($username, $password)
                        ->post($url, $data);

        // Return the response from Elasticsearch
        if ($response->successful()) {
            return response()->json([
                'message' => 'User created successfully in Elasticsearch',
                'data' => $response->json()
            ], 201);
        } else {
            return response()->json([
                'message' => 'Failed to create user in Elasticsearch',
                'error' => $response->json()
            ], $response->status());
        }
    }
}
