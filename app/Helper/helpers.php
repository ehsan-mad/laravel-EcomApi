<?php

use App\Helper\ResponseHelper;

if (!function_exists('responseSuccess')) {
    /**
     * Return a success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function responseSuccess($data = null, string $message = 'Success', int $statusCode = 200)
    {
        return ResponseHelper::success($data, $message, $statusCode);
    }
}

if (!function_exists('responseError')) {
    /**
     * Return an error response
     *
     * @param string $message
     * @param mixed $errors
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function responseError(string $message = 'Error', $errors = null, int $statusCode = 400)
    {
        return ResponseHelper::error($message, $errors, $statusCode);
    }
}

if (!function_exists('responseValidationError')) {
    /**
     * Return a validation error response
     *
     * @param mixed $errors
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responseValidationError($errors, string $message = 'Validation failed')
    {
        return ResponseHelper::validationError($errors, $message);
    }
}

if (!function_exists('responseUnauthorized')) {
    /**
     * Return an unauthorized response
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responseUnauthorized(string $message = 'Unauthorized')
    {
        return ResponseHelper::unauthorized($message);
    }
}

if (!function_exists('responseForbidden')) {
    /**
     * Return a forbidden response
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responseForbidden(string $message = 'Forbidden')
    {
        return ResponseHelper::forbidden($message);
    }
}

if (!function_exists('responseNotFound')) {
    /**
     * Return a not found response
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responseNotFound(string $message = 'Resource not found')
    {
        return ResponseHelper::notFound($message);
    }
}

if (!function_exists('responseCreated')) {
    /**
     * Return a created response
     *
     * @param mixed $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responseCreated($data = null, string $message = 'Resource created successfully')
    {
        return ResponseHelper::created($data, $message);
    }
}

if (!function_exists('responsePaginated')) {
    /**
     * Return a paginated response
     *
     * @param mixed $data
     * @param array $pagination
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responsePaginated($data, array $pagination, string $message = 'Data retrieved successfully')
    {
        return ResponseHelper::paginated($data, $pagination, $message);
    }
}

if (!function_exists('responseCollection')) {
    /**
     * Return a response for API resource collection
     *
     * @param mixed $collection
     * @param string $message
     * @param array $meta
     * @return \Illuminate\Http\JsonResponse
     */
    function responseCollection($collection, string $message = 'Data retrieved successfully', array $meta = [])
    {
        return ResponseHelper::collection($collection, $message, $meta);
    }
}

if (!function_exists('responseResource')) {
    /**
     * Return a response for single API resource
     *
     * @param mixed $resource
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responseResource($resource, string $message = 'Data retrieved successfully')
    {
        return ResponseHelper::resource($resource, $message);
    }
}