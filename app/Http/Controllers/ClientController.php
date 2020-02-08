<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientCollection;
use App\Http\Resources\Client as ClientResource;

use App\Client;

class ClientController extends Controller
{
    public function index()
    {
        return new ClientCollection(Client::all());
    }

    public function show(Client $client)
    {
        return new ClientResource($client);
    }

    public function create(ClientRequest $request)
    {
        $client = Client::create($request->validated());

        return response()->json($client, 201);
    }

    public function delete(Client $client)
    {
        $client->delete();

        return response()->json(null, 204);
    }
}
