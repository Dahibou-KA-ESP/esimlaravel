<?php

namespace App\Http\Repositories;

use App\Models\Client;

class ClientRepository extends BaseRepository
{
    function __construct(){
		$this->model=new Client();
	}
}
