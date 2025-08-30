<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
class ResponseHelper
{
    // Focused 90% use cases: success, error, auth/validation variants, created, collection, resource.

    public static function success($data = null, string $message = 'Success', int $statusCode = Response::HTTP_OK): JsonResponse
    { return response()->json(['status'=>'success','message'=>$message,'data'=>$data], $statusCode); }

    public static function error(string $message = 'Error', $errors = null, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    { return response()->json(array_filter(['status'=>'error','message'=>$message,'errors'=>$errors]), $statusCode); }

    public static function validationError($errors, string $message = 'Validation failed'): JsonResponse
    { return response()->json(['status'=>'error','message'=>$message,'errors'=>$errors], Response::HTTP_UNPROCESSABLE_ENTITY); }

    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    { return response()->json(['status'=>'error','message'=>$message], Response::HTTP_UNAUTHORIZED); }

    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    { return response()->json(['status'=>'error','message'=>$message], Response::HTTP_FORBIDDEN); }

    public static function notFound(string $message = 'Resource not found'): JsonResponse
    { return response()->json(['status'=>'error','message'=>$message], Response::HTTP_NOT_FOUND); }

    public static function created($data = null, string $message = 'Resource created successfully'): JsonResponse
    { return response()->json(['status'=>'success','message'=>$message,'data'=>$data], Response::HTTP_CREATED); }

    public static function collection($collection, string $message = 'Data retrieved successfully', array $meta = []): JsonResponse
    { return response()->json(array_filter(['status'=>'success','message'=>$message,'data'=>$collection,'meta'=>empty($meta)?null:$meta]), Response::HTTP_OK); }

    public static function resource($resource, string $message = 'Data retrieved successfully'): JsonResponse
    { return response()->json(['status'=>'success','message'=>$message,'data'=>$resource], Response::HTTP_OK); }
}