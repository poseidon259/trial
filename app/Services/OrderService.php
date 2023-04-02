<?php

namespace App\Services;

use App\Repositories\ChildMasterField\ChildMasterFieldRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
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


    public function __construct(
        OrderRepositoryInterface $orderRepositoryInterface,
        OrderItemRepositoryInterface $orderItemRepositoryInterface,
        ChildMasterFieldRepositoryInterface $childMasterFieldRepositoryInterface,
        ProductRepositoryInterface $productRepositoryInterface
    ) {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
        $this->orderItemRepositoryInterface = $orderItemRepositoryInterface;
        $this->childMasterFieldRepositoryInterface = $childMasterFieldRepositoryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
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
}
