<?php

namespace App\Services\Returned;

use App\DTOs\Returned\ReturnedStoreDto;

use App\Exceptions\BreakException;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Returned\ReturnedRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Support\Facades\Gate;

readonly class ReturnedService implements ReturnedServiceInterface
{
    public function __construct
    (
        private OrderRepositoryInterface     $orderRepository,
        private OrderItemRepositoryInterface $orderItemRepository,
        private ReturnedRepositoryInterface  $returnedRepository,
        private S3ServiceInterface           $s3Service,
    )
    {
    }

    public function store(ReturnedStoreDto $dto): mixed
    {
        $orderId = $dto->order_id;
        $orderItemId = $dto->order_item_id;
        $userId = $dto->userId;
        $count = $dto->count;
        $description = $dto->description;
        $file = $dto->file;
        $order = $this->orderRepository->findOrFail($orderId);
        $orderItem = $this->orderItemRepository->findOrFail($orderItemId);
        $returned = $this->returnedRepository->findByOrderItemId($orderItemId);
        Gate::authorize("returned", $order);

        if ($returned)
            throw new BreakException();
        if ($orderItem->order_id != $order->id)
            throw new BreakException();
        if ($orderItem->count < $count)
            throw new BreakException();

        $filePath = "";
        if ($file) {
            $filePath = $this->s3Service->upload($file, "returned");
        }
        $this->returnedRepository->createReturned($orderId, $orderItemId, $userId, $count, $description, $filePath);

    }

    public function reject($id)
    {
        $returned = $this->returnedRepository->findOrFail($id);
        Gate::allows("isPending", $returned);
        return $this->returnedRepository->setReject($returned);
    }

    public function accept($id)
    {
        $returned = $this->returnedRepository->findOrFail($id);
        Gate::allows("isPending", $returned);
        return $this->returnedRepository->setAccept($returned);
    }
    public function dataTable(): mixed
    {
        return $this->returnedRepository->dataTable();
    }
}
