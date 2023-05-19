<?php

namespace App\Repositories\Order;

use App\Repositories\Base\BaseRepositoryInterface;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function detail($id);

    public function getListOrder($request);

    public function detailOrderPublic($id, $userId);

    public function listOrderHistory($request, $userId);

    public function dashboard();
}
