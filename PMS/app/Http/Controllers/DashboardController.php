<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
class DashboardController extends Controller
{
    public function index()
    {
        if (session()->has('user')) {

            // Get the logged-in user's email from the session
            $userEmail = session('user')['email'];

            // Elasticsearch endpoint and credentials
            $url = 'http://localhost:9200/post/_search';
            $username = 'elastic';
            $password = 'elastic';

            // Define the search query to filter by user_id
            $searchQuery = [
                'query' => [
                    'term' => [
                        'email.keyword' => $userEmail
                    ]
                ]
            ];

            // Send the POST request to Elasticsearch
            $response = Http::withBasicAuth($username, $password)
                ->post($url, $searchQuery);

            // Check if the request was successful
            if ($response->successful()) {
                $posts = $response->json()['hits']['hits'];
                $posts = array_map(function ($post) {
                    return $post['_source'];
                }, $posts);

                return view('dashboard', ['posts' => $posts]);
            } else {
                return response()->json(['error' => 'Failed to fetch posts'], 500);
            }
        } else {
            return redirect('/login');
        }
    }

    public function post(Request $request)
    {
        $username = 'elastic';
        $password = 'elastic';
        $data = [
            "email" => session('user')['email'],
            "post" => $request->post
        ];
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

    public function edit($email)
    {
        // Fetch the user by email from Elasticsearch or your database
        // For example, using Elasticsearch
        $url = 'http://localhost:9200/users/_search';
        $username = 'elastic';
        $password = 'elastic';

        $searchQuery = [
            'query' => [
                'term' => [
                    'email.keyword' => $email
                ]
            ]
        ];

        $searchResponse = Http::withBasicAuth($username, $password)
            ->post($url, $searchQuery);

        if ($searchResponse->successful() && !empty($searchResponse['hits']['hits'])) {
            $user = $searchResponse['hits']['hits'][0]['_source'];
            return view('edit', compact('user'));
        } else {
            return redirect('/dashboard')->with('error', 'User not found');
        }
    }

    public function update(Request $request)
    {
        $url = 'http://localhost:9200/users/_update_by_query';
        $username = 'elastic';
        $password = 'elastic';

        // Prepare the update query
        $updateQuery = [
            'script' => [
                'source' => 'ctx._source.name = params.name','ctx._source.password = params.password',
                'params' => [
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    // Add more fields to update if necessary
                ]
            ],
            'query' => [
                'term' => [
                    'email.keyword' => $request->email
                ]
            ]
        ];

        // Send the update request
        $response = Http::withBasicAuth($username, $password)
            ->post($url, $updateQuery);

        // Check if the update was successful
        if ($response->successful()) {
            return redirect('/dashboard')->with('message', 'User updated successfully');
        } else {
            return redirect('/dashboard')->with('error', 'Failed to update user');
        }
    }

    public function delete($email)
{
    $url = 'http://localhost:9200/users/_delete_by_query';
    $username = 'elastic';
    $password = 'elastic';

    // Prepare the delete query
    $deleteQuery = [
        'query' => [
            'term' => [
                'email.keyword' => $email
            ]
        ]
    ];


    $response = Http::withBasicAuth($username, $password)
        ->post($url, $deleteQuery);

    
    if ($response->successful()) {
        return redirect('/dashboard')->with('message', 'User deleted successfully');
    } else {
        return redirect('/dashboard')->with('error', 'Failed to delete user');
    }
}

}
