<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../services/OrdersDetailsService.php';

Flight::route('GET /api/order-details', function() {
    $orderDetailsService = new OrderDetailsService();
    echo json_encode($orderDetailsService->get_all_order_details());
});

Flight::route('GET /api/order-details/order/@orderID', function($orderID) {
    $orderDetailsService = new OrderDetailsService();
    echo json_encode($orderDetailsService->get_order_details_by_order_id($orderID));
});

Flight::route('POST /api/order-details', function() {
    $data = Flight::request()->data->getData();
    $orderDetailsService = new OrderDetailsService();
    try {
        $orderDetailsService->add_order_details($data);
        echo json_encode(['message' => 'Order details added successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

Flight::route('PUT /api/order-details', function() {
    $data = Flight::request()->data->getData();
    $orderDetailsService = new OrderDetailsService();
    try {
        $orderDetailsService->update_order_details($data);
        echo json_encode(['message' => 'Order details updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

Flight::route('DELETE /api/order-details/@id', function($id) {
    $orderDetailsService = new OrderDetailsService();
    try {
        $orderDetailsService->delete_order_details($id);
        echo json_encode(['message' => 'Order details deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});
