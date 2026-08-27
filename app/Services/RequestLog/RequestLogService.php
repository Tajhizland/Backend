<?php

namespace App\Services\RequestLog;

use App\DTOs\RequestLog\RequestLogStoreDto;
use App\Repositories\RequestLog\RequestLogRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class RequestLogService implements RequestLogServiceInterface
{
    public function __construct(private RequestLogRepositoryInterface $requestLogRepository)
    {
    }

    public function store(RequestLogStoreDto $dto): mixed
    {
        return $this->requestLogRepository->store(
            $dto->title,
            $this->normalize($dto->request),
            $this->normalize($dto->response)
        );
    }

    public function log(?string $title, mixed $request = null, mixed $response = null): void
    {
        try {
            $this->store(new RequestLogStoreDto($title, $request, $response));
        } catch (Throwable $e) {
            Log::warning("request-log failed: " . $e->getMessage(), ["title" => $title]);
        }
    }

    public function dataTable()
    {
        return $this->requestLogRepository->dataTable();
    }

    private function normalize(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            return (string)$value;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
    }
}
