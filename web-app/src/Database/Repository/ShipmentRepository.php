<?php

namespace App\Database\Repository;


class ShipmentRepository extends GenericRepository
{
    public static function countUnderWayShipments()
    {
        return 'call count_shipments_under_way()';
    }

    public static function countShipmentsByState()
    {
        return 'call count_shipments_by_state(@archived, @under_way, @done)';
    }

    public static function archive()
    {
        return 'archive_shipments()';
    }

    public static function getShipmentsByWarehouse(string $warehouse): string
    {
        return 'call get_shipments_by_warehouse("' . $warehouse . '")';
    }
}