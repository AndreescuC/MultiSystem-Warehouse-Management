<?php

namespace App\Dto;

class WarehouseDto
{
    public $id;

    public $name;

    public $location;

    public $capacity;

    public function setName(string $name): WarehouseDto
    {
        $this->name = $name;
        return $this;
    }

    public function setLocation(string $location): WarehouseDto
    {
        $this->location = $location;
        return $this;
    }

    public function setCapacity(int $capacity): WarehouseDto
    {
        $this->capacity = $capacity;
        return $this;
    }
}