<?php

namespace App\Manager;

use App\Entity\Order;

class OrderManager extends AbstractManager
{
    public function generateOrder(?Order $order, $user, array $payloads = [])
    {
        $order = $order ?? new Order();

    }
}
