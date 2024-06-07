<?php

namespace App\Http\Controllers;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.host') . ':' . config('elasticsearch.port')])
            ->build();
    }
    public function createUserIndex()
    {
        $params = [
            'index' => 'users',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'name' => ['type' => 'text'],
                        'email' => ['type' => 'keyword'],
                        'password' => ['type' => 'text'] // Store post IDs
                    ]
                ]
            ]
        ];

        $response = $this->client->indices()->create($params);

        return response()->json($response);
    }
}
