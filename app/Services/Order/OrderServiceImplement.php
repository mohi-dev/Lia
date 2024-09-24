<?php

namespace App\Services\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\Service;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use App\Http\Resources\OrderResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class OrderServiceImplement extends Service implements OrderService{

    use \App\Traits\ResultService;
    /**
     * Initial related repositories for this service
     */
    public function __construct(
        public OrderRepository $orderRepository,
        public ProductRepository $productRepository,
    ) {}

    public function all(): OrderServiceImplement
    {
        try {
            return $this->setResult(OrderResource::collection($this->orderRepository->all()))
                ->setCode(Response::HTTP_OK)
                ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function create($data)
    {
        try {
            $this->syncStockAccordingToOrder($data['products']);

            return $this->setResult(new OrderResource($this->orderRepository->create($data)))
                    ->setMessage('')
                    ->setCode(Response::HTTP_CREATED)
                    ->setStatus(true);
        }catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }


    public function findOrFail($id): OrderServiceImplement
    {
        try {
            return $this->setResult($this->orderRepository->findOrFail($id))
                ->setCode(Response::HTTP_OK)
                ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function update($id, array $data): OrderServiceImplement
    {
        try {
            $this->syncStockAccordingToOrder($data['products']);
            $this->orderRepository->update($id, $data);
            return $this->setResult(new OrderResource($this->orderRepository->FindOrFail($id)))
                ->setMessage('')
                ->setCode(Response::HTTP_OK)
                ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function delete($id): OrderServiceImplement
    {
        try {
            $this->orderRepository->delete($id);
            return $this->setMessage('')
                ->setCode(Response::HTTP_OK)
                ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    private function syncStockAccordingToOrder($products)
    {
        /**
         * TODO: We need to use transactions but my machine doesn't meet the software minimums
         */
        foreach($products as $product) {
            $quantity = $product['quantity'];
            $product = $this->productRepository->findOrFail($product['id']);
            $inventory = $product->inventory - $quantity;

            if($inventory < 0){
                throw ValidationException::withMessages(
                    ['products' => __('validation.out_of_stock')]
                );
            }
            
            $this->productRepository->update($product->id, ['inventory' => $inventory]);
        }
    }
}
