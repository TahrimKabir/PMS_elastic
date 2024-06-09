<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $url = 'http://localhost:9200/users/_search';
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

        $searchResponse = Http::withOptions(['verify' => false])
            ->withBasicAuth($username, $password)
            ->post($url, $searchQuery);

        // Check if the search was successful and if the user exists
        if ($searchResponse->successful() && !empty($searchResponse['hits']['hits'])) {
            $user = $searchResponse['hits']['hits'][0]['_source'];

            // Verify the password
            if (Hash::check($request->password, $user['password'])) {
                // Password matches, return success response
                $data = array('email' => $request->email, 'name' => $user['name']);
                session()->put('user', $data);
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
    }

    public function logout(Request $request){
        Session::flush();
        return redirect('/login');
    }
}
