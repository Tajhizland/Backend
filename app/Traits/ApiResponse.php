<?php

namespace App\Traits;

trait ApiResponse
{
    protected function dataResponse($data, $message = "با موفقیت دریافت شد")
    {
        return response()->json($this->successEnvelope(true, ["data" => $data], $message), 200);
    }

    protected function dataResponseCollection($data, $message = "با موفقیت دریافت شد")
    {
        return response()->json($this->successEnvelope(true, $data->response()->getData(), $message), 200);
    }

    protected function successResponse($message = 'OK')
    {
        return response()->json($this->successEnvelope(true, [], $message));
    }

    protected function redirectResponse($destination)
    {
        return response()->json($this->successEnvelope(true, ["destination" => $destination], "انتقال"), 301);
    }

    protected function badRequestResponse($message = 'درخواست نامعتبر', $errors = 'درخواست نا معتبر')
    {
        return response()->json($this->errorEnvelope(false, $errors, $message), 400);
    }

    protected function forbiddenResponse($errors = 'Forbidden', $message = 'Forbidden')
    {
        return response()->json($this->errorEnvelope(false, $errors, $message), 403);
    }

    protected function notFindResponse($message = 'موردی یافت نشد', $errors = 'موردی یافت نشد')
    {
        return response()->json($this->errorEnvelope(false, $errors, $message), 404);
    }

    private function successEnvelope($success = true, $data = [], $message = 'OK')
    {
        return [
            'success' => $success,
            'message' => $message,
            'result' => $data,
        ];
    }

    private function errorEnvelope($success = false, $errors = [], $message = 'Bad Request')
    {
        return [
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
        ];
    }
}
