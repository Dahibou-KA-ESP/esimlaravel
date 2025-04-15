<?php

namespace App\Http\Repositories;

use App\Helpers\Constante;

class BaseRepository
{
    protected $model;

	function __construct($model=null){
	    $this->model=$model;
	}

    public function getPaginate($n,$step=Constante::STEP_PAGINATE)
	{
	    return $this->model::orderBy('created_at','desc')->paginate($step,['*'],'',$n);
	}

    public function create(array $inputs)
	{
		return $this->model->create($inputs);
	}

    public function getById($id)
	{
		return $this->model->findOrFail($id);
	}

    public function update(array $inputs,$id)
	{
		return $this->find($id)->update($inputs);
	}

    public function delete($id)
	{
		return $this->find($id)->delete();
	}

    public function getFirstBy($column,$value){
		return $this->model->where($column,$value)->first();
	}

    public function getBy($column,$value){
	    return $this->model->where($column,$value)->get();
	}

    public function getOneBy($column,$value){
	    return $this->model->where($column,$value)->first();
	}

    public function find($id)
	{
		return $this->model->findOrFail($id);
	}

    public function all()
	{
	    return $this->model::orderBy('created_at','desc')->get();
	}
}
