<?php

if (!function_exists('getPaymentMethod')) {
    /**
     * @return int
     */
    function getPaymentMethod($method)
    {
        switch ($method) {
            case PAYMENT_METHOD_COD:
                return 'Ship COD';
            case PAYMENT_METHOD_VISA:
                return 'Visa/Mastercard';
            case PAYMENT_METHOD_VNPAY:
                return 'VNPay';
        }
    }
}

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

if (!function_exists('getPrice')) {

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    function getPrice($salePrice, $originPrice)
    {
        return $salePrice ? $salePrice : $originPrice;
    }
}
