<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../services/OrdersService.php';

// Get all orders
Flight::route('GET /api/orders', function() {
    $ordersService = new OrdersService();
    echo json_encode($ordersService->get_all_orders());
});

// Get an order by ID
Flight::route('GET /api/orders/@id', function($id) {
    $ordersService = new OrdersService();
    echo json_encode($ordersService->get_order_by_id($id));
});

// Add a new order
Flight::route('POST /api/orders', function() {
    $data = Flight::request()->data->getData();
    $ordersService = new OrdersService();
    try {
        $ordersService->add_order($data);
        echo json_encode(['message' => 'Order placed successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Update an existing order
Flight::route('PUT /api/orders', function() {
    $data = Flight::request()->data->getData();
    $ordersService = new OrdersService();
    try {
        $ordersService->update_order($data);
        echo json_encode(['message' => 'Order updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Delete an order
Flight::route('DELETE /api/orders/@id', function($id) {
    $ordersService = new OrdersService();
    try {
        $ordersService->delete_order($id);
        echo json_encode(['message' => 'Order cancelled successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});
