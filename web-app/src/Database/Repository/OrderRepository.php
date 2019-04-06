<?php

namespace App\Database\Repository;


class OrderRepository extends GenericRepository
{
    public static function validateOrder(int $id): string
    {
        return 'call validate_order(' . $id . ')';
    }

    public static function countShippingOrders()
    {
        return 'call count_shipping_orders()';
    }

    public static function countOrdersByState()
    {
        return 'call count_orders_by_state(@archived, @under_way, @done)';
    }

    public static function archive()
    {
        return 'call archive_orders()';
    }

    public static function getOrdersByWarehouse(string $warehouse): string
    {
        return 'call get_orders_by_warehouse("' . $warehouse . '")';
    }
}