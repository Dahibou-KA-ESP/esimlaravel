<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ClientRepository;
use App\Http\Requests\ClientRegsiterRequest;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Auth;

class ClientController extends BaseController
{
    private $repo;
    public function __construct(ClientRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(){
        $clients = ClientResource::collection($this->repo->all());
        return $this->sendSuccess($clients);
    }

    public function show($id){
        $client = new ClientResource($this->repo->getById($id));
        return $this->sendSuccess($client);
    }

    public function store(ClientRegsiterRequest $request){
        $client = new ClientResource($this->repo->create($request->all()));
        return $this->sendSuccess($client);
    }

    public function update(ClientRegsiterRequest $request,$id){
        $client = new ClientResource($this->repo->update($request->all(),$id));
        return $this->sendSuccess($client);
    }

    public function destroy($id){
        $client = $this->repo->delete($id);
        return $this->sendSuccess($client);
    }

}
