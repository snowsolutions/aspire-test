<?php

namespace App\Http\Response;

class ApiResponseHandler
{
    /**
     * This show the error state with custom error code & message
     * @param int $statusCode
     * @param string $message
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errors(int $statusCode, string $message, $errors = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    /**
     * This show the success state with custom message & data in response
     * @param string $message
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, string $message = ""): \Illuminate\Http\JsonResponse
    {
        $response = [];
        if (!is_null($data)) {
            $response['data'] = $data;
        }
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response);
    }

    /**
     * This simply show the success state after created a resource
     * @return \Illuminate\Http\JsonResponse
     */
    public static function contentCreated(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 201,
            'message' => __('Content Created'),
        ], 201);
    }

    /**
     * This simply show the success state after deleted a resource
     * @return \Illuminate\Http\JsonResponse
     */
    public static function noContent(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 204,
            'message' => __('No Content'),
        ], 204);
    }

    /**
     * This show the bad request error to client or a custom error message
     * @param $message
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function badRequest($message = null, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 400,
            'message' => is_null($message) ? __('Bad Requests') : $message,
            'data' => $data,
        ], 400);
    }

    /**
     * This simply show the forbidden access error to client
     * @return \Illuminate\Http\JsonResponse
     */
    public static function forbidden(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 403,
            'message' => __('Forbidden access'),
        ], 403);
    }

    /**
     * This simply show the unauthorized error to client
     * @return \Illuminate\Http\JsonResponse
     */
    public static function unauthorized(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 401,
            'message' => __('Unauthorized'),
        ], 401);
    }

    /**
     * This mean the server understand your request but could not process due to some reason
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function unProcessableEntity(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 422,
            'message' => $message
        ], 422);
    }

    /**
     * This mean the application has caught an exception that lead to internal errors
     * @param string $message
     * @param $exceptions
     * @return \Illuminate\Http\JsonResponse
     */
    public static function exception(string $message, $exceptions = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 500,
            'message' => $message,
            'exceptions' => $exceptions
        ], 500);
    }
}
