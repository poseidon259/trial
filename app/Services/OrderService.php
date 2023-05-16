<?php

namespace App\Services;

use App\Repositories\ChildMasterField\ChildMasterFieldRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductInformation\ProductInformationRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepositoryInterface;

    /**
     * @var OrderItemRepositoryInterface
     */
    private $orderItemRepositoryInterface;

    /**
     * @var ChildMasterFieldRepositoryInterface
     */
    private $childMasterFieldRepositoryInterface;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    /**
     * @var ProductInformationRepositoryInterface
     */
    private $productInfomationRepositoryInterface;

    /**
     * @var MailService
     */
    private $mailService;


    public function __construct(
        OrderRepositoryInterface            $orderRepositoryInterface,
        OrderItemRepositoryInterface        $orderItemRepositoryInterface,
        ChildMasterFieldRepositoryInterface $childMasterFieldRepositoryInterface,
        ProductRepositoryInterface          $productRepositoryInterface,
        ProductInformationRepositoryInterface $productInfomationRepositoryInterface,
        MailService                         $mailService
    )
    {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
        $this->orderItemRepositoryInterface = $orderItemRepositoryInterface;
        $this->childMasterFieldRepositoryInterface = $childMasterFieldRepositoryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->productInfomationRepositoryInterface = $productInfomationRepositoryInterface;
        $this->mailService = $mailService;
    }

    public function create($request)
    {
        if (isset($request->order_items)) {
            $shippingFee = DELIVERY_FEE;
            $paramItems = [];
            $subTotal = 0;
            foreach ($request->order_items as $item) {
                if (isset($item['child_master_field_id']) && $item['child_master_field_id'] != null) {
                    $product = $this->childMasterFieldRepositoryInterface->detail($item['product_id'], $item['child_master_field_id']);

                    if ($product) {
                        $paramItems[] = [
                            'product_id' => $item['product_id'],
                            'child_master_field_id' => $item['child_master_field_id'],
                            'product_name' => $product->product_name,
                            'sale_price' => $product->sale_price,
                            'origin_price' => $product->origin_price,
                            'quantity' => $item['quantity'],
                            'sub_total' => getPrice($product->sale_price, $product->origin_price) * $item['quantity'],
                            'total' => $productTotal = getPrice($product->sale_price, $product->origin_price) * $item['quantity'],
                        ];

                        $subTotal += $productTotal;
                    }
                } else {
                    $product = $this->productRepositoryInterface->detail($item['product_id']);

                    if ($product) {
                        $paramItems[] = [
                            'product_id' => $item['product_id'],
                            'child_master_field_id' => null,
                            'product_name' => $product->name,
                            'sale_price' => $product->sale_price,
                            'origin_price' => $product->origin_price,
                            'quantity' => $item['quantity'],
                            'sub_total' => getPrice($product->sale_price, $product->origin_price) * $item['quantity'],
                            'total' => $productTotal = getPrice($product->sale_price, $product->origin_price) * $item['quantity'],
                        ];

                        $subTotal += $productTotal;
                    }
                }
            }

            $orderNo = Str::orderedUuid()->toString();
            $total = $subTotal + $shippingFee;
            $params = [
                'order_no' => $orderNo,
                'note' => $request->note,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'delivery_fee' => DELIVERY_FEE,
                'status' => ORDER_NEW,
                'sub_total' => $subTotal,
                'total' => $total,
                'user_id' => $request->user_id ?? null,
            ];

            $order = $this->orderRepositoryInterface->create($params);
            $order->orderItems()->createMany($paramItems);

            return _success(null, __('messages.create_success'), HTTP_SUCCESS);
        }

        return _error(null, __('messages.order_items_empty'), HTTP_BAD_REQUEST);
    }

    public function delete($id)
    {
        $order = $this->orderRepositoryInterface->find($id);

        if (!$order) {
            return _error(null, __('messages.order_not_found'), HTTP_BAD_REQUEST);
        }

        $this->orderRepositoryInterface->delete($id);

        return _success(null, __('messages.delete_success'), HTTP_SUCCESS);
    }

    public function show($id)
    {
        $order = $this->orderRepositoryInterface->find($id);

        if (!$order) {
            return _error(null, __('messages.order_not_found'), HTTP_BAD_REQUEST);
        }

        $order = $this->orderRepositoryInterface->detail($id);

        return _success($order, __('messages.success'), HTTP_SUCCESS);
    }

    public function getList($request)
    {
        $limit = $request->limit ?? LIMIT;
        $page = $request->page ?? PAGE;

        $orders = $this->orderRepositoryInterface->getListOrder($request)->paginate($limit, $page);

        return [
            'orders' => $orders->items(),
            'total' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
        ];
    }

    public function createOrderByUser($request, $user)
    {
        if (isset($request->order_items)) {
            $shippingFee = $request->shipping_fee ?? DELIVERY_FEE;
            $paramItems = [];
            $subTotal = 0;
            foreach ($request->order_items as $item) {
                if (isset($item['child_master_field_id']) && $item['child_master_field_id'] != null) {
                    $product = $this->childMasterFieldRepositoryInterface->detail($item['product_id'], $item['child_master_field_id']);

                    if ($product) {
                        $paramItems[] = [
                            'product_id' => $item['product_id'],
                            'child_master_field_id' => $item['child_master_field_id'],
                            'product_name' => $product->product_name,
                            'sale_price' => $product->sale_price,
                            'origin_price' => $product->origin_price,
                            'quantity' => $item['quantity'],
                            'sub_total' => getPrice($product->sale_price, $product->origin_price) * $item['quantity'],
                            'total' => $productTotal = getPrice($product->sale_price, $product->origin_price) * $item['quantity'],
                        ];

                        $subTotal += $productTotal;
                    }
                } else {
                    $product = $this->productRepositoryInterface->detail($item['product_id']);

                    if ($product) {
                        $paramItems[] = [
                            'product_id' => $item['product_id'],
                            'child_master_field_id' => null,
                            'product_name' => $product->name,
                            'sale_price' => $product->sale_price,
                            'origin_price' => $product->origin_price,
                            'quantity' => $item['quantity'],
                            'sub_total' => getPrice($product->sale_price, $product->origin_price) * $item['quantity'],
                            'total' => $productTotal = getPrice($product->sale_price, $product->origin_price) * $item['quantity'],
                        ];

                        $subTotal += $productTotal;
                    }
                }
            }

            $orderNo = Str::orderedUuid()->toString();
            $total = $subTotal + $shippingFee;
            $params = [
                'order_no' => $orderNo,
                'note' => $request->note,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'house_number' => $request->house_number,
                'delivery_fee' => $shippingFee,
                'payment_method' => $request->payment_method,
                'status' => ORDER_NEW,
                'sub_total' => $subTotal,
                'total' => $total,
                'user_id' => $user->id,
                'payment_note' => $request->payment_note ?? "Blank"
            ];

            $order = $this->orderRepositoryInterface->create($params);
            $order->orderItems()->createMany($paramItems);

            $this->updateQuantityProduct($paramItems);

            $data = [
                'order' => $order,
                'order_items' => $paramItems
            ];

            $this->mailService->sendEmail(
                $request->email,
                $data,
                __('messages.title_send_mail_order'),
                'mail.create_order'
            );

            return _success(null, __('messages.create_success'), HTTP_SUCCESS);
        }

        return _error(null, __('messages.order_items_empty'), HTTP_BAD_REQUEST);
    }

    public function updateQuantityProduct($paramItems) {
        foreach ($paramItems as $item) {
            if (isset($item['child_master_field_id']) && $item['child_master_field_id'] != null) {
                $product = $this->childMasterFieldRepositoryInterface->detail($item['product_id'], $item['child_master_field_id']);

                if ($product) {
                    $stock = $product->stock - $item['quantity'];
                    $this->childMasterFieldRepositoryInterface->update($item['child_master_field_id'], ['stock' => $stock]);
                }
            } else {
                $product = $this->productRepositoryInterface->detail($item['product_id']);

                if ($product) {
                    $stock = $product->stock - $item['quantity'];
                    $this->productInfomationRepositoryInterface->update($product->product_information_id, ['stock' => $stock]);
                }
            }
        }
    }

    public function paymentVNPay($orderId)
    {
        $order = $this->orderRepositoryInterface->find($orderId);

        if (is_null($order)) {
            return _error(null, __('messages.order_not_found'), HTTP_BAD_REQUEST);
        }

        if ($order->status != ORDER_NEW) {
            return _error(null, __('messages.order_status_invalid'), HTTP_BAD_REQUEST);
        }

        $url = Redis::get($order->order_no);

        if (is_null($url)) {
            $now = now();
            $vnPayCode = $order->order_no;

            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = getenv('CUSTOMER_URL') . "/checkout/payment_vn_pay";
            $vnp_TmnCode = "GAPL1ENK";
            $vnp_HashSecret = "KXGTIVJUADOUQQTGQOEPZCMYIEEUCHHQ";
            $vnp_IpAddr = request()->ip();
            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $order->total * 100,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => $now->format('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => "vn",
                "vnp_OrderInfo" => $order->payment_note ?? "Noi dung thanh toan",
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnPayCode,
            ];

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }
            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            Redis::set($order->order_no, $vnp_Url);
        }

        $url = Redis::get($order->order_no);
        return $url;
    }

    public function checkPaymentVnpay($request)
    {
        $vnp_HashSecret = "KXGTIVJUADOUQQTGQOEPZCMYIEEUCHHQ";
        $vnp_SecureHash = $request->vnp_SecureHash;

        $inputData = [];

        foreach ($request->query as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                return _success(null, __('messages.vnpay_message_success'), HTTP_SUCCESS);
            } else {
                return _error(null, __('messages.vnpay_message_error'), HTTP_BAD_REQUEST);
            }
        } else {
            return _error(null, __('messages.vnpay_message_signature_error'), HTTP_BAD_REQUEST);
        }
    }

    public function ipnVNPay($request)
    {
        $vnp_HashSecret = "KXGTIVJUADOUQQTGQOEPZCMYIEEUCHHQ";
        $vnp_SecureHash = $request->vnp_SecureHash;

        $inputData = [];

        foreach ($request->query as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
        $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
        $vnp_Amount = $inputData['vnp_Amount'] / 100; // Số tiền thanh toán VNPAY phản hồi
        $vnpayStatus = 0; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo URL thanh toán.
        $orderNo = $inputData['vnp_TxnRef'];


        //Kiểm tra checksum của dữ liệu
        if ($secureHash == $vnp_SecureHash) {
            $order = $this->orderRepositoryInterface->findOne('order_no', $orderNo);

            if (!is_null($order)) {
                if ($order->total == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch
                {
                    if ($order->status == ORDER_NEW) {
                        if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                            $vnpayStatus = 1; // Trạng thái thanh toán thành công
                        } else {
                            $vnpayStatus = 2; // Trạng thái thanh toán thất bại / lỗi
                        }
                        //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB
                        $now = now();
                        $params = [
                            'status' => ORDER_PAID,
                            'payment_date' => $now
                        ];

                        $this->orderRepositoryInterface->update($order->id, $params);

                        //Trả kết quả về cho VNPAY: Website/APP TMĐT ghi nhận yêu cầu thành công
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'Confirm Success';
                    } else {
                        $returnData['RspCode'] = '02';
                        $returnData['Message'] = 'Order already confirmed';

                        return _error($returnData, __('messages.order_updated_fail'), HTTP_BAD_REQUEST);
                    }
                } else {
                    $returnData['RspCode'] = '04';
                    $returnData['Message'] = 'invalid amount';

                    return _error($returnData, __('messages.order_updated_fail'), HTTP_BAD_REQUEST);
                }
            } else {
                $returnData['RspCode'] = '01';
                $returnData['Message'] = 'Order not found';

                return _error($returnData, __('messages.order_not_found'), HTTP_BAD_REQUEST);
            }
        } else {
            $returnData['RspCode'] = '97';
            $returnData['Message'] = 'Invalid signature';

            return _error($returnData, __('messages.order_updated_fail'), HTTP_BAD_REQUEST);
        }

        return _success($returnData, __('messages.order_updated_success'), HTTP_SUCCESS);
    }

    public function detailOrderPublic($id, $user)
    {
        $order = $this->orderRepositoryInterface->find($id);

        if (!$order) {
            return _error(null, __('messages.order_not_found'), HTTP_BAD_REQUEST);
        }

        $order = $this->orderRepositoryInterface->detailOrderPublic($id, $user->id);

        return _success($order, __('messages.success'), HTTP_SUCCESS);
    }

    public function listOrderHistory($request, $user)
    {
        $limit = $request->limit ?? LIMIT;
        $page = $request->page ?? PAGE;

        $orders = $this->orderRepositoryInterface->listOrderHistory($request, $user->id)->paginate($limit, $page);

        return [
            'orders' => $orders->items(),
            'total' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
        ];
    }

    public function updateStatusOrder($request, $id) {
        $order = $this->orderRepositoryInterface->find($id);

        if (!$order) {
            return _error(null, __('messages.order_not_found'), HTTP_BAD_REQUEST);
        }

        $params = [
            'status' => $request->status
        ];

        $this->orderRepositoryInterface->update($request->id, $params);

        return _success(null, __('messages.order_updated_success'), HTTP_SUCCESS);
    }
}
