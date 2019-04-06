<?php

namespace App\Controller;

use App\Database\Adaptor;
use App\Service\WarehouseManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HttpController extends AbstractController
{
    private $dbAdaptor;

    private $managementService;

    public function __construct(Adaptor $adaptor, WarehouseManagementService $managementService)
    {
        $this->dbAdaptor         = $adaptor;
        $this->managementService = $managementService;
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction(Request $request)
    {
        $warehouses = $this->dbAdaptor->fetchAll('warehouse');
        $stats      = $this->managementService->getDashboardStats();

        return $this->render('index.twig', [
            'warehouses' => $warehouses,
            'stats'      => $stats
        ]);
    }

    /**
     * @Route("/warehouse/{name}", name="warehouse")
     */
    public function warehouseAction(Request $request)
    {
        $imgUrl = 'http://businessnc.com/wp-content/uploads/2018/06/warehouse_getty-623362614.jpg';
        return $this->render('warehouse.twig', [
            'warehouse_name' => $request->get('name'),
            'img_url'        => $imgUrl
        ]);
    }

    /**
     * @Route("/warehouse/{warehouse_name}/shipments", name="shipment-list")
     */
    public function shipmentListAction(Request $request)
    {
        $warehouseName = $request->get('warehouse_name');
        return $this->render('shipments.twig', [
            'warehouse_name' => $warehouseName,
            'shipments'      => $this->dbAdaptor->getShipmentsByWarehouse($warehouseName)
        ]);
    }

    /**
     * @Route("/warehouse/{warehouse_name}/orders", name="orders-list")
     */
    public function orderListAction(Request $request)
    {
        $warehouseName = $request->get('warehouse_name');
        return $this->render('orders.twig', [
            'warehouse_name' => $warehouseName,
            'orders'         => $this->dbAdaptor->getOrdersByWarehouse($warehouseName)
        ]);
    }

    /**
     * @Route("/admin-panel", name="admin-panel")
     */
    public function adminPanelAction(Request $request)
    {
        return $this->render('panel.twig', [
            'stats' => $this->managementService->getArchiveStats()
        ]);
    }

    /**
     * @Route("/archive/shipments", name="archive-shipments")
     */
    public function archiveShipmentsAction(Request $request)
    {
        $this->dbAdaptor->archiveShipments();
        return new JsonResponse("Success!");
    }

    /**
     * @Route("/archive/orders", name="archive-orders")
     */
    public function archiveOrdersAction(Request $request)
    {
        $this->dbAdaptor->archiveOrders();
        return new JsonResponse("Success!");
    }
}
