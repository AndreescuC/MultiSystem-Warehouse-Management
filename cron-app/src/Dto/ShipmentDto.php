<?php

namespace App\Dto;

class ShipmentDto
{
    public const STATUS_ASSIGNED  = 0;
    public const STATUS_ON_COURSE = 1;
    public const STATUS_DONE      = 2;

    public $id;

    public $warehouseId;

    public $status;

    public function setWarehouseId(int $warehouseId): ShipmentDto
    {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    public function setStatus(int $status): ShipmentDto
    {
        $this->status = $status;
        return $this;
    }
}