<?php

if (!function_exists('_error')) {
    /**
     * @param $data
     * @param $message
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function _error($data = null, $message = null, $statusCode = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}

if (!function_exists('_success')) {
    /**
     * @param $data
     * @param $message
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function _success($data = null, $message = null, $statusCode = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}

if (!function_exists('_errorSystem')) {

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    function _errorSystem()
    {
        return _error(null, __('messages.error_system'), HTTP_BAD_REQUEST);
    }
}