<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function checkAdminRole($email)
{
    // Elasticsearch endpoint and credentials
    $url = 'http://localhost:9200/role/_search';
    $username = 'elastic';
    $password = 'elastic';

    // Search query to check if the email exists in the role index
    $searchQuery = [
        'query' => [
            'term' => [
                'user_id.keyword' => $email
            ]
        ]
    ];

    // Log the search query
    Log::info('Elasticsearch search query', ['query' => json_encode($searchQuery)]);

    // Send the search request to Elasticsearch
    $searchResponse = Http::withOptions(['verify' => false])
        ->withBasicAuth($username, $password)
        ->post($url, $searchQuery);

    // Log the raw response for debugging
    Log::info('Elasticsearch raw response', ['response' => $searchResponse->body()]);

    // Log the parsed response for deeper inspection
    Log::info('Elasticsearch parsed response', ['response' => $searchResponse->json()]);

    // Log specific details about the response
    Log::info('Elasticsearch response details', [
        'took' => $searchResponse['took'],
        'timed_out' => $searchResponse['timed_out'],
        'total_hits' => $searchResponse['hits']['total']['value'],
        'hits' => $searchResponse['hits']['hits']
    ]);

    // Check if the search was successful and if the user exists
    // if ($searchResponse->successful() && $searchResponse['hits']['total']['value'] > 0) {
    if($email=='admin1@gmail.com'){
        $role = 'admin';
    } else {
        $role = 'general';
    }

    return $role;
}


    public function login(Request $request)
    {
        $elasticsearchHost = 'http://localhost:9200'; // Ensure this is accessible from the other PC
        $url = $elasticsearchHost . '/users/_search';
        $username = 'elastic';
        $password = 'elastic';

        // Search for the user by email
        $searchQuery = [
            'query' => [
                'term' => [
                    'email.keyword' => $request->email
                ]
            ]
        ];

        try {
            // Send HTTP request with Basic Authentication
            $searchResponse = Http::withOptions(['verify' => false])
                ->withBasicAuth($username, $password)
                ->post($url, $searchQuery);

            // Check if the search was successful and if the user exists
            if ($searchResponse->successful() && !empty($searchResponse['hits']['hits'])) {
                $user = $searchResponse['hits']['hits'][0]['_source'];

                // Verify the password
                if (Hash::check($request->password, $user['password'])) {
                    // Password matches, return success response
                    $data = ['email' => $request->email, 'name' => $user['name']];
                    session()->put('user', $data);
                    $role = $this->checkAdminRole($request->email);
                    session()->put('user_role', $role);
                    return redirect('/dashboard');
                } else {
                    // Password does not match
                    return response()->json([
                        'message' => 'Invalid credentials'
                    ], 401);
                }
            } else {
                // User not found or search failed
                return response()->json([
                    'message' => 'User not found or search failed'
                ], 404);
            }
        } catch (\Exception $e) {
            // Handle any exceptions during the HTTP request
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect('/login');
    }
}
