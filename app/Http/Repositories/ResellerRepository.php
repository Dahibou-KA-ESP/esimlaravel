<?php

namespace App\Http\Repositories;

use App\Models\Reseller;

class ResellerRepository extends BaseRepository
{
    function __construct(){
		$this->model=new Reseller();
	}
}
