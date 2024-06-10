<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class DashboardController extends Controller
{
    public function index(){
        if(session()->has('user')){
            
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
                    $posts = array_map(function($post) {
                        return $post['_source'];
                    }, $posts);
        
                    return view('dashboard', ['posts' => $posts]);
                } else {
                    return response()->json(['error' => 'Failed to fetch posts'], 500);
                }
            
            
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
