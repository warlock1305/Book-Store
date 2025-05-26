<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../data/Roles.php';

Flight::group('/api/orders', function () {

    // Get all orders - allow USER and ADMIN
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::orders_service()->get_all_orders());
    });

    // Get an order by ID - allow USER and ADMIN
    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::orders_service()->get_order_by_id($id));
    });

    // Add a new order - only ADMIN
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::orders_service()->add_order($data);
            echo json_encode(['message' => 'Order placed successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Update an existing order - only ADMIN
    Flight::route('PUT /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::orders_service()->update_order($data);
            echo json_encode(['message' => 'Order updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Delete an order - only ADMIN
    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        try {
            Flight::orders_service()->delete_order($id);
            echo json_encode(['message' => 'Order cancelled successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

});
