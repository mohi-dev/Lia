<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductRequest;
use App\Services\Product\ProductService;

class ProductController extends Controller
{

    /**
     * Initial related services for this controller
     */
    public function __construct(
        public ProductService $productService,
    ) {}


    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {   
        return \Cache::remember('products', 100000, function ()  {
            return $this->productService->all()->toJson();
        });

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        return $this->productService->create($request->validated())->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id): JsonResponse
    {
        return $this->productService->findOrFail($id)->toJson();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string|int $id): JsonResponse
    {
        return $this->productService->update($id, $request->validated())->toJson();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id): JsonResponse
    {
        return $this->productService->delete($id)->toJson();
    }

}
