<?php

namespace App\Interfaces;
use App\Http\Requests\UpdateProductRequest;
interface ProductRepositoryInterface
{
    public function index();
    public function getById($id);
    public function store(array $data);
    public function update($id, UpdateProductRequest $data);
    public function delete($id);
}
