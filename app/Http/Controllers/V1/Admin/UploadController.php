<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Upload\AbortUploadRequest;
use App\Http\Requests\V1\Admin\Upload\CompleteUploadRequest;
use App\Http\Requests\V1\Admin\Upload\InitiateUploadRequest;
use App\Http\Requests\V1\Admin\Upload\SignPartsRequest;
use App\Services\DirectUpload\DirectUploadServiceInterface;
use Illuminate\Support\Facades\Auth;

/**
 * آپلود مستقیم مرورگر به S3.
 *
 * هیچ‌کدام از این اکشن‌ها بدنه‌ی فایل را دریافت نمی‌کنند؛ فقط URL امضاشده
 * صادر و در پایان آبجکت را تأیید می‌کنند.
 */
class UploadController extends Controller
{
    public function __construct(
        private DirectUploadServiceInterface $directUploadService
    )
    {
    }

    public function initiate(InitiateUploadRequest $request)
    {
        return $this->dataResponse(
            $this->directUploadService->initiate(
                $request->get("profile"),
                $request->get("fileName"),
                (int)$request->get("size"),
                (string)$request->get("mime", ""),
                Auth::user()->id
            )
        );
    }

    public function signParts(SignPartsRequest $request)
    {
        return $this->dataResponse([
            'urls' => $this->directUploadService->signParts(
                $request->get("key"),
                $request->get("partNumbers"),
                Auth::user()->id
            ),
        ]);
    }

    public function complete(CompleteUploadRequest $request)
    {
        return $this->dataResponse(
            $this->directUploadService->complete(
                $request->get("key"),
                $request->get("parts") ?? [],
                Auth::user()->id
            )
        );
    }

    public function abort(AbortUploadRequest $request)
    {
        $this->directUploadService->abort($request->get("key"), Auth::user()->id);

        return $this->successResponse("آپلود لغو شد.");
    }
}
