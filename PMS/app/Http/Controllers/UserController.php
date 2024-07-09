<?php

namespace App\Http\Controllers;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
class UserController extends Controller
{
    public function index()
    { if(Session::get('user_role')=='admin'){
        $url = 'http://localhost:9200/users/_search';
        $username = 'elastic';
        $password = 'elastic';
    
        // Prepare the search query to fetch all users
        $searchQuery = [
            'query' => [
                'match_all' => (object)[]
            ],
            'size' => 1000 // Adjust the size to the number of users you expect
        ];
    
        // Send the search request
        $searchResponse = Http::withOptions(['verify' => false])
            ->withBasicAuth($username, $password)
            ->post($url, $searchQuery);
    
        // Check if the search was successful
        if ($searchResponse->successful()) {
            $users = $searchResponse['hits']['hits'];
    
            // Extract user data
            $userList = [];
            foreach ($users as $user) {
                $userList[] = $user['_source'];
            }
    
            // Pass user data to the view
            return view('user-list', compact('userList'));
        } else {
            return redirect('/dashboard')->with('error', 'Failed to fetch users');
        }
    }else{
        return response()->json('you have no access for this page');
    }
    }
    
    public function createAdmin() {
        // Define user data
        $data = [
            'name' => 'superadmin',
            'email' => 'admin1@gmail.com',
            'password' => '$2a$12$kgP6MNcRItjQYBnv85FojeAvPD8noFeE7k/JzcnoXPb1kTGllVv/u',
        ];
    
        // Elasticsearch endpoint and credentials
        $url = 'http://localhost:9200/users/_search';
        $username = 'elastic';
        $password = 'elastic';
    
        // Check if the user already exists in Elasticsearch
        $searchQuery = [
            'query' => [
                'term' => [
                    'email.keyword' => 'admin1@gmail.com'
                ]
            ]
        ];
    
        // Send the search request to Elasticsearch
        $searchResponse = Http::withOptions(['verify' => false])
            ->withBasicAuth($username, $password)
            ->post($url, $searchQuery);
    
        // If the search is successful and the user exists, return a conflict error
        if ($searchResponse->successful() && $searchResponse['hits']['total']['value'] > 0) {
            return response()->json([
                'message' => 'User with this email already exists'
            ], 409);
        }
    
        // User does not exist, proceed to create the user
        $url = 'http://localhost:9200/users/_doc';
    
        // Send the POST request to Elasticsearch to create the user
        $createResponse = Http::withOptions(['verify' => false])
            ->withBasicAuth($username, $password)
            ->post($url, $data);
    
        // Check if the user creation was successful
        if ($createResponse->successful()) {
            // Define role data
            $dataRole = [
                'user_id' => 'admin1@gmail.com',
                'role' => 'admin',
            ];
    
            // Role URL
            $roleUrl = 'http://localhost:9200/role/_doc';
    
            // Send the POST request to Elasticsearch to create the user role
            $createResponserole = Http::withOptions(['verify' => false])
                ->withBasicAuth($username, $password)
                ->post($roleUrl, $dataRole);
    
            return response()->json([
                'message' => 'User created successfully in Elasticsearch',
                'data' => $createResponse->json(),
                'role' => $createResponserole->json(),
            ], 201);
        } else {
            // Handle user creation failure
            return response()->json([
                'message' => 'Failed to create user in Elasticsearch',
                'error' => $createResponse->json()
            ], $createResponse->status());
        }
    }
    

    
}
