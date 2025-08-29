<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponseHelper
{
    /**
     * Return a success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Return an error response
     *
     * @param string $message
     * @param mixed $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $message = 'Error', $errors = null, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a validation error response
     *
     * @param mixed $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function validationError($errors, string $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Return an unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Return a forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Return a not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Return a server error response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Return a created response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], Response::HTTP_CREATED);
    }

    /**
     * Return a no content response
     *
     * @return JsonResponse
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Return a paginated response
     *
     * @param mixed $data
     * @param array $pagination
     * @param string $message
     * @return JsonResponse
     */
    public static function paginated($data, array $pagination, string $message = 'Data retrieved successfully'): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination
        ], Response::HTTP_OK);
    }

    /**
     * Return a response with custom status and data structure
     *
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function custom(array $data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    /**
     * Return a response for API resource collection
     *
     * @param mixed $collection
     * @param string $message
     * @param array $meta
     * @return JsonResponse
     */
    public static function collection($collection, string $message = 'Data retrieved successfully', array $meta = []): JsonResponse
    {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $collection
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Return a response for single API resource
     *
     * @param mixed $resource
     * @param string $message
     * @return JsonResponse
     */
    public static function resource($resource, string $message = 'Data retrieved successfully'): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $resource
        ], Response::HTTP_OK);
    }

    public static function Out($msg,$data,$code):JsonResponse{
    return  response()->json(['msg' => $msg, 'data' =>  $data],$code);
  }
}