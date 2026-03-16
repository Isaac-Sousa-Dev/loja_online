<?php

namespace App\Repository;

abstract class AbstractRepository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($data)
    {
        return $this->model->find($data['id'])->update($data);
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    // public function insert(array $data)
    // {
    //     return $this->model::insert($data);
    // }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    protected function resolveModel()
    {
        return app($this->model);
    }


}
