<?php

namespace App\Database\Repository;


class GenericRepository
{
    public function fetchAll(string $entity): string
    {
        return 'call fetch_all("' . $entity . '")';
    }

    public function countAll(string $entity): string
    {
        return 'call count_all("' . $entity . '")';
    }

    public function markOneAs(string $entity, int $id, int $status): string
    {
        return 'call mark_one_as("' . $entity . '", "' . $id . '", "' . $status . '")';
    }

    public function removeOneById(string $entity, int $id): string
    {
        return 'call delete_one_by_id("' . $entity . '", "' . $id . '")';
    }
}
