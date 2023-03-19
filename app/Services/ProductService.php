<?php

namespace App\Services;

use App\Repositories\Product\ProductRepositoryInterface;

class ProductService
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface
    )
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function create($request)
    {
        $checkExists = $this->productRepositoryInterface->findOne('');
    }

    public function update($request, $id)
    {
       
    }

    public function delete($id)
    {

    }

    public function list($request)
    {
      
    }

    public function show($id)
    {
        
    }

}