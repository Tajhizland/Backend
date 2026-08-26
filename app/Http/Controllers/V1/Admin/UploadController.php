<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Upload\UploadAbortDto;
use App\DTOs\Upload\UploadCompleteDto;
use App\DTOs\Upload\UploadInitiateDto;
use App\DTOs\Upload\UploadSignPartsDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Upload\AbortUploadRequest;
use App\Http\Requests\Admin\Upload\CompleteUploadRequest;
use App\Http\Requests\Admin\Upload\InitiateUploadRequest;
use App\Http\Requests\Admin\Upload\SignPartsRequest;
use App\Services\DirectUpload\DirectUploadServiceInterface;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    public function __construct(
        private readonly DirectUploadServiceInterface $directUploadService,
    )
    {
    }

    public function initiate(InitiateUploadRequest $request)
    {
        $dto = new UploadInitiateDto(Auth::user()->id, ...$request->validated());
        return $this->dataResponse($this->directUploadService->initiate($dto));
    }

    public function signParts(SignPartsRequest $request)
    {
        $dto = new UploadSignPartsDto(Auth::user()->id, ...$request->validated());
        return $this->dataResponse(['urls' => $this->directUploadService->signParts($dto)]);
    }

    public function complete(CompleteUploadRequest $request)
    {
        $dto = new UploadCompleteDto(Auth::user()->id, ...$request->validated());
        return $this->dataResponse($this->directUploadService->complete($dto));
    }

    public function abort(AbortUploadRequest $request)
    {
        $dto = new UploadAbortDto(Auth::user()->id, ...$request->validated());
        $this->directUploadService->abort($dto);
        return $this->successResponse("آپلود لغو شد.");
    }
}
