<?php

namespace App\Interfaces;

interface AbstractServiceInterface  
{
    public function insert(array $data, $request = null);
    public function update($data, $model);
    public function delete($model);
    public function find($id);
    public function findAll();
}
