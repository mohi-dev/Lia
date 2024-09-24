<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\Service;
use App\Repositories\Product\ProductRepository;
use App\Http\Resources\ProductResource;
use Symfony\Component\HttpFoundation\Response;

class ProductServiceImplement extends Service implements ProductService{


    use \App\Traits\ResultService;
    
    public function __construct(
        public ProductRepository $productRepository,
    ) {}

    public function all(): ProductServiceImplement
    {
        try {
            return $this->setResult(
                ProductResource::collection(
                    $this->productRepository->all()
                ))
                ->setCode(Response::HTTP_OK)
                ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Create an item
     * @param array|mixed $data
     * @return Model|null
     */
    public function create($data): ProductServiceImplement
    {
        try {
            return $this->setResult(new ProductResource($this->productRepository->create($data)))
                    ->setMessage('')
                    ->setCode(Response::HTTP_CREATED)
                    ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }


        /**
     * Find an item by id or fail
     * @param mixed $id
     * @return Model|null
     */
    public function findOrFail($id): ProductServiceImplement
    {
        try {
            $result = $this->productRepository->findOrFail($id);
            return $this->setResult($result)
                ->setCode(Response::HTTP_OK)
                ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Update a model
     * @param int|mixed $id
     * @param array|mixed $data
     * @return bool|mixed
     */
    public function update($id, array $data): ProductServiceImplement
    {
        try {
            $this->productRepository->update($id, $data);
            return $this->setResult(new ProductResource($this->productRepository->FindOrFail($id)))
                ->setMessage('')
                ->setCode(Response::HTTP_OK)
                ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

     /**
     * Delete a model
     * @param int|Model $id
     */
    public function delete($id): ProductServiceImplement
    {
        try {
            $this->productRepository->delete($id);
            return $this->setMessage('')
                ->setCode(Response::HTTP_OK)
                ->setStatus(true);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
