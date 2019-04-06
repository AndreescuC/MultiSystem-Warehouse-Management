<?php

namespace App\Service;


use App\Database\Adaptor;

class WarehouseManagementService
{
    private $dbAdaptor;

    public function __construct(Adaptor $adaptor)
    {
        $this->dbAdaptor = $adaptor;
    }

    public function getDashboardStats()
    {
        return [
            'under_way_shipments' => $this->dbAdaptor->getUnderWayShipments(),
            'under_way_orders'    => $this->dbAdaptor->getUnderWayOrders(),
            'warehouses'          => $this->dbAdaptor->countAll('warehouse'),
            'total_stock'         => $this->dbAdaptor->getTotalStockSold()
        ];
    }

    public function getArchiveStats()
    {
        return [
            'shipment_stats' => $this->dbAdaptor->getShipmentStats(),
            'orders_stats'   => $this->dbAdaptor->getOrderHeaderStats()
        ];
    }
}