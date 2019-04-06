<?php

namespace App\Database;

use App\Database\Repository\GenericRepository;
use App\Database\Repository\OrderLineRepository;
use App\Database\Repository\OrderRepository;
use App\Database\Repository\ShipmentRepository;
use App\Database\Repository\WarehouseRepository;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class Adaptor
{
    private $warehouseRepo;

    private $orderRepo;

    private $shipmentRepo;

    private $orderLineRepo;

    private $connection;

    private $entityManager;

    private $genericRepo;

    public function __construct(
        Connection $connection,
        EntityManagerInterface $entityManager,
        WarehouseRepository $warehouseRepo,
        ShipmentRepository $shipmentRepo,
        OrderRepository $orderRepo,
        GenericRepository $genericRepo,
        OrderLineRepository $orderLineRepo
    ) {
        $this->warehouseRepo = $warehouseRepo;
        $this->orderRepo     = $orderRepo;
        $this->shipmentRepo  = $shipmentRepo;
        $this->orderLineRepo = $orderLineRepo;
        $this->genericRepo   = $genericRepo;
        $this->connection    = $connection;
        $this->entityManager = $entityManager;
    }

    public function getUnderWayShipments()
    {
        $call = $this->shipmentRepo::countUnderWayShipments();
        return (int)$this->callAndReturnFunction($call);
    }

    public function getTotalStockSold()
    {
        $call = $this->orderLineRepo::getTotalStockSold();
        return (int)$this->callAndReturnFunction($call);
    }

    public function getUnderWayOrders()
    {
        $call = $this->orderRepo::countShippingOrders();
        return (int)$this->callAndReturnFunction($call);
    }

    public function getShipmentsByWarehouse(string $warehouse): array
    {
        $call = $this->shipmentRepo::getShipmentsByWarehouse($warehouse);
        return $this->callAndFetchProcedure($call);
    }

    public function getOrdersByWarehouse(string $warehouse): array
    {
        $call = $this->orderRepo::getOrdersByWarehouse($warehouse);
        return $this->callAndFetchProcedure($call);
    }

    public function getShipmentStats()
    {
        $call = $this->shipmentRepo::countShipmentsByState();
        $stats = $this->callAndFetchProcedure($call, [
            [
                'name'  => 'archived',
                'out'   => true
            ],
            [
                'name'  => 'under_way',
                'out'   => true
            ],
            [
                'name' => 'done',
                'out'    => true
            ]
        ]);

        return [
            (int)$stats[0]['@archived'],
            (int)$stats[1]['@under_way'],
            (int)$stats[2]['@done']
        ];
    }

    public function getOrderHeaderStats()
    {
        $call = $this->orderRepo::countOrdersByState();
        $stats = $this->callAndFetchProcedure($call, [
            [
                'name'  => 'archived',
                'out'   => true
            ],
            [
                'name'  => 'under_way',
                'out'   => true
            ],
            [
                'name' => 'done',
                'out'    => true
            ]
        ]);

        return [
            (int)$stats[0]['@archived'],
            (int)$stats[1]['@under_way'],
            (int)$stats[2]['@done']
        ];
    }

    public function archiveShipments()
    {
        $this->callAndReturnFunction($this->shipmentRepo::archive());
    }

    public function archiveOrders()
    {
        $this->callProcedure($this->orderRepo::archive());
    }

    public function fetchAll(string $entity): array
    {
        try {
            $call = $this->genericRepo->fetchAll($entity);
            return $this->callAndFetchProcedure($call);
        } catch (\Exception $e) {
            //TODO: log this
            return [];
        }
    }

    public function countAll(string $entity): ?int
    {
        try {
            $call = $this->genericRepo->countAll($entity);
            $response = $this->callAndFetchProcedure($call);
            return (int)$response[0]['count(*)'];
        } catch (\Exception $e) {
            //TODO: log this
            return null;
        }
    }

    public function markOneAs(string $entity, int $id, int $status): void
    {
        try {
            $call = $this->genericRepo->markOneAs($entity, $id, $status);
            $this->callProcedure($call);
        } catch (\Exception $e) {
            //TODO: log this
        }
    }

    public function removeOneById(string $entity, int $id): void
    {
        try {
            $call = $this->genericRepo->removeOneById($entity, $id);
            $this->callProcedure($call);
        } catch (\Exception $e) {
            //TODO: log this
        }
    }

    private function callAndFetchProcedure(string $call, array $parameters = []): ?array
    {
        $stmt = $this->connection->prepare($call);
        if ($parameters) {
            foreach ($parameters as $parameter) {
                if ($parameter['out']) {
                    continue;
                }
                $stmt->bindParam($parameter['name'], $parameter['value']);
            }
        }
        $stmt->execute();

        $outArguments = [];
        if ($parameters) {
            foreach ($parameters as $parameter) {
                if (!$parameter['out']) {
                    continue;
                }
                $outArguments[] = $this->connection->query("select @" . $parameter['name'])->fetch(\PDO::FETCH_ASSOC);
            }
        }

        try {
            return $stmt->fetchAll();
        } catch (\PDOException $exception) {
            if ($exception->getErrorCode() == 'HY000') {
                return $outArguments;
            } else {
                //TODO: log this
                return null;
            }
        }
    }

    private function callProcedure(string $call)
    {
        $stmt = $this->connection->prepare($call);
        $stmt->execute();
    }

    private function callAndReturnFunction(string $call, array $parameters = [])
    {
        $mapping = (new ResultSetMapping())->addScalarResult('result', 'result');
        $sql = 'select ' . $call . ' as result';
        $query = $this->entityManager->createNativeQuery($sql, $mapping);
        if ($parameters) {
            $query->setParameters($parameters);
        }
        try {
            return $query->getSingleScalarResult();
        } catch (\Exception $e) {
            //TODO: log this
            return null;
        }
    }
}