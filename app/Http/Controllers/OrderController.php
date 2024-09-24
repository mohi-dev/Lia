<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\OrderRequest;
use App\Services\Order\OrderService;

class OrderController extends Controller
{
    /**
     * Initial related services for this controller
     */
    public function __construct(
        public OrderService $orderService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {   
        return \Cache::remember('orders', 100000, function ()  {
            return $this->orderService->all()->toJson();
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request): JsonResponse
    {
        return $this->orderService->create($request->validated())->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id): JsonResponse
    {
        return $this->orderService->findOrFail($id)->toJson();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, string|int $id): JsonResponse
    {
        return $this->orderService->update($id, $request->validated())->toJson();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id): JsonResponse
    {
        return $this->orderService->delete($id)->toJson();
    }
}
