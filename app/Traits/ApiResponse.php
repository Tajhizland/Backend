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
        $response = $this->successEnvelope(true, $data, 'Created');

        return response()->json($response, 201);
    }

    protected function updatedResponse($data)
    {
        $response = $this->successEnvelope(true, $data, 'Updated');

        return response()->json($response, 200);
    }

    protected function dataResponse($data , $message="success")
    {
        $response = $this->successEnvelope(true, ["data"=>$data], $message);

        return response()->json($response, 200);
    }
   protected function dataResponseCollection($data , $message="success")
    {
        $response = $this->successEnvelope(true, $data, $message);

        return response()->json($response, 200);
    }

    /**
     * Returns general error
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequestResponse($errors , $message='Bad Request')
    {
        $response = $this->errorEnvelope(false, $errors ,$message);

        return response()->json($response, 400);
    }

    /**
     * Client does not have proper permissions to perform action.
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function forbiddenResponse($errors ,$message='Forbidden')
    {
        $response = $this->errorEnvelope(false, $errors,
            $message);

        return response()->json($response, 403);
    }
    protected function unauthorizedResponse($errors ,$message='Unauthorized')
    {
        $response = $this->errorEnvelope(false, $errors,
            $message);

        return response()->json($response, 401);
    }

    /**
     * Returns a list of resources
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse( $message= 'OK')
    {
        $response = $this->successEnvelope(true ,[],$message);

        return response()->json($response);
    }
    protected function errorResponse( $message= 'error')
    {
        $response = $this->errorEnvelope(false ,[],$message);

        return response()->json($response , 400);
    }

    /**
     * Requested resource wasn't found
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundResponse()
    {
        $response = $this->errorEnvelope(false, [], 'Not Found');

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
        $response = $this->errorEnvelope(false, $errors, 'Unprocessable Entity');

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
        $success = false,
        $errors = [],
        $message = 'Bad Request'
    ) {
        return [
            'success' => $success,
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
        $success = true,
        $data = [],
        $message = 'OK'
    ) {
        return [
            'success' => $success,
            'message' => $message,
            'result' => $data,
        ];
    }

}
