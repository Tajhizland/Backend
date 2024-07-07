<?php

namespace App\Traits;

/**
 * Class ApiResponseTrait
 *
 * @package App\Traits
 * @author Allan Kiezel <allan.kiezel@gmail.com>
 */
trait ApiResponse
{

    /**
     * Resource was successfully created
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createdResponse($data)
    {
        $response = $this->successEnvelope(201, $data, 'Created');

        return response()->json($response, 201);
    }

    protected function updatedResponse($data)
    {
        $response = $this->successEnvelope(200, $data, 'Updated');

        return response()->json($response, 200);
    }

    /**
     * Returns general error
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequestResponse($errors)
    {
        $response = $this->errorEnvelope(400, $errors);

        return response()->json($response, 400);
    }

    /**
     * Client does not have proper permissions to perform action.
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function ForbiddenResponse($errors)
    {
        $response = $this->errorEnvelope(403, $errors,
            'Forbidden');

        return response()->json($response, 403);
    }

    /**
     * Returns a list of resources
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data)
    {
        $response = $this->successEnvelope(200, $data);

        return response()->json($response);
    }

    /**
     * Requested resource wasn't found
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundResponse()
    {
        $response = $this->errorEnvelope(404, [], 'Not Found');

        return response()->json($response, 404);
    }

    /**
     * Return error when request is properly formatted, but contains validation errors
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrorResponse($errors)
    {
        $response = $this->errorEnvelope(422, $errors, 'Unprocessable Entity');

        return response()->json($response, 422);
    }

    /**
     * Standard error envelope structure
     *
     * @param int $status
     * @param array $errors
     * @param string $message
     * @return array
     */
    private function errorEnvelope(
        $status = 400,
        $errors = [],
        $message = 'Bad Request'
    ) {
        return [
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
        ];
    }

    /**
     * Standard success envelope structure
     *
     * @param int $status
     * @param array $data
     * @param string $message
     * @return array
     */
    private function successEnvelope(
        $status = 200,
        $data = [],
        $message = 'OK'
    ) {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
    }

}
