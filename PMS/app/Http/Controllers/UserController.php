<?php

namespace App\Http\Controllers;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class UserController extends Controller
{
    public function index()
    {
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
        $searchResponse = Http::withBasicAuth($username, $password)
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
    }
    
    public function createAdmin(){
        
    }

    
}
