<?php

namespace App\Dto;

class ProductDto
{
    public $id;

    public $name;

    public $documentation;

    public $storageInfo;

    public $volumeCategory;

    public $weightCategory;

    public function setName(string $name): ProductDto
    {
        $this->name = $name;
        return $this;
    }

    public function setDocumentation(string $doc): ProductDto
    {
        $this->documentation = $doc;
        return $this;
    }

    public function setStorageInfo(string $stgInfo): ProductDto
    {
        $this->storageInfo = $stgInfo;
        return $this;
    }

    public function setVolumeCategory(int $volumeCategory): ProductDto
    {
        $this->volumeCategory = $volumeCategory;
        return $this;
    }

    public function setWeightCategory(string $weightCategory): ProductDto
    {
        $this->weightCategory = $weightCategory;
        return $this;
    }
}