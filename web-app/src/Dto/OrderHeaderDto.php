<?php

namespace App\Dto;

class OrderHeaderDto
{
    public const STATUS_PLACED    = 0;
    public const STATUS_CONFIRMED = 1;
    public const STATUS_DONE      = 2;

    public const IS_RETURN_TRUE  = 0;
    public const IS_RETURN_FALSE = 1;

    public $id;

    public $shipmentId;

    public $warehouseId;

    public $status;

    public $address;

    public $isReturn;

    public function setShipmentId(int $shipmentId): OrderHeaderDto
    {
        $this->shipmentId = $shipmentId;
        return $this;
    }

    public function setWarehouseId(int $warehouseId): OrderHeaderDto
    {
        $this->warehouseId= $warehouseId;
        return $this;
    }

    public function setStatus(int $status): OrderHeaderDto
    {
        $this->status = $status;
        return $this;
    }

    public function setAddress(string $address): OrderHeaderDto
    {
        $this->address = $address;
        return $this;
    }

    public function setIsReturn(int $isReturn): OrderHeaderDto
    {
        $this->isReturn = $isReturn;
        return $this;
    }
}