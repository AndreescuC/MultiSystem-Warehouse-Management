<?php

namespace App\Dto;

class WarehouseProductDto
{
    public $id;

    public $productId;

    public $warehouseId;

    public $stock;

    public $reservedStock;

    public function setProductId(int $productId): WarehouseProductDto
    {
        $this->productId = $productId;
        return $this;
    }

    public function setWarehouseId(int $warehouseId): WarehouseProductDto
    {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    public function setStock(int $stock): WarehouseProductDto
    {
        $this->stock = $stock;
        return $this;
    }

    public function setReservedStock(int $reservedStock): WarehouseProductDto
    {
        $this->reservedStock = $reservedStock;
        return $this;
    }
}