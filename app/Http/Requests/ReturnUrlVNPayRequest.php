<?php

namespace App\Http\Requests;

class ReturnUrlVNPayRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'vnp_Amount' => 'required|integer',
            'vnp_BankCode' => 'required|string',
            'vnp_BankTranNo' => 'required|string',
            'vnp_CardType' => 'required|string',
            'vnp_OrderInfo' => 'required|string',
            'vnp_PayDate' => 'required|string',
            'vnp_ResponseCode' => 'required|string',
            'vnp_TmnCode' => 'required|string',
            'vnp_TransactionNo' => 'required|string',
            'vnp_TransactionStatus' => 'required|string',
            'vnp_TxnRef' => 'required|string',
            'vnp_SecureHash' => 'required|string'
        ];
    }
}
