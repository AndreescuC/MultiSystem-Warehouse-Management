<?php

namespace App\Controller;

use App\Database\Adaptor;
use App\Dto\OrderHeaderDto;
use App\Dto\ShipmentDto;
use App\Service\WarehouseManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends AbstractController
{
    private $dbAdaptor;

    private $managementService;

    public function __construct(Adaptor $adaptor, WarehouseManagementService $managementService)
    {
        $this->dbAdaptor         = $adaptor;
        $this->managementService = $managementService;
    }

    /**
     * @Route("/order/remove/{warehouse}/{id}", name="remove-order")
     */
    public function removeOrderAjaxAction(Request $request)
    {
        $warehouseName = $request->get('warehouse');
        $orderId       = $request->get('id');

        $this->dbAdaptor->removeOneById('order_header', $orderId);

        return $this->render('orders.twig', [
            'warehouse_name' => $warehouseName,
            'orders'         => $this->dbAdaptor->getOrdersByWarehouse($warehouseName)
        ]);
    }

    /**
     * @Route("/order/mark/{warehouse}/{id}", name="mark-order")
     */
    public function markOrderAjaxAction(Request $request)
    {
        $warehouseName = $request->get('warehouse');
        $orderId       = $request->get('id');

        $this->dbAdaptor->markOneAs('order_header', $orderId, OrderHeaderDto::STATUS_DONE);

        return $this->render('orders.twig', [
            'warehouse_name' => $warehouseName,
            'orders'         => $this->dbAdaptor->getOrdersByWarehouse($warehouseName)
        ]);
    }


    /**
     * @Route("/shipment/mark-shipping/{warehouse}/{id}", name="mark-shipping-shipment")
     */
    public function markShipmentOnCourseAjaxAction(Request $request)
    {
        $warehouseName = $request->get('warehouse');
        $shipmentId    = $request->get('id');

        $this->dbAdaptor->markOneAs('shipment', $shipmentId, ShipmentDto::STATUS_ON_COURSE);

        return $this->render('shipments.twig', [
            'warehouse_name' => $warehouseName,
            'shipments'      => $this->dbAdaptor->getShipmentsByWarehouse($warehouseName)
        ]);
    }

    /**
     * @Route("/shipment/mark-done/{warehouse}/{id}", name="mark-done-shipment")
     */
    public function markShipmentDoneAjaxAction(Request $request)
    {
        $warehouseName = $request->get('warehouse');
        $shipmentId    = $request->get('id');

        $this->dbAdaptor->markOneAs('shipment', $shipmentId, ShipmentDto::STATUS_DONE);

        return $this->render('shipments.twig', [
            'warehouse_name' => $warehouseName,
            'shipments'      => $this->dbAdaptor->getShipmentsByWarehouse($warehouseName)
        ]);
    }

    /**
     * @Route("/shipment/remove/{warehouse}/{id}", name="remove_shipment")
     */
    public function removeShipmentAjaxAction(Request $request)
    {
        $warehouseName = $request->get('warehouse');
        $shipmentId    = $request->get('id');

        $this->dbAdaptor->removeOneById('shipment', $shipmentId);

        return $this->render('shipments.twig', [
            'warehouse_name' => $warehouseName,
            'shipments'      => $this->dbAdaptor->getShipmentsByWarehouse($warehouseName)
        ]);
    }
}
