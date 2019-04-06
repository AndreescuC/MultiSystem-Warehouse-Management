<?php

namespace App\Dto;

class OrderLineDto
{
    public $id;

    public $productId;

    public $orderHeaderId;

    public $orderedStock;

    public $warehouseProductReservedStock;

    public function setProductId(int $productId): OrderLineDto
    {
        $this->productId = $productId;
        return $this;
    }

    public function setOrderHeaderId(string $orderHeaderId): OrderLineDto
    {
        $this->orderHeaderId = $orderHeaderId;
        return $this;
    }

    public function setOrderedStock(string $stock): OrderLineDto
    {
        $this->orderedStock = $stock;
        return $this;
    }

    public function setWarehouseProductReservedStock(int $stock): OrderLineDto
    {
        $this->warehouseProductReservedStock = $stock;
        return $this;
    }
}