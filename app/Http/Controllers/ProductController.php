<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;

class ProductController
{
    private ProductRepositoryInterface $ProductRepositoryInterface;

    public function __construct(ProductRepositoryInterface $ProductRepositoryInterface){
        $this->ProductRepositoryInterface = $ProductRepositoryInterface;
    }

    public function show($id) {
        $Product = $this->ProductRepositoryInterface->getById($id);
        return view('Product.Product', compact('Product'));
    }
}
