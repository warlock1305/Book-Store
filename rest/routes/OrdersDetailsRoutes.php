<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../data/Roles.php';

Flight::group('/api/order-details', function () {

    // Get all order details - allow USER and ADMIN
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::orders_details_service()->get_all_order_details());
    });

    // Get order details by order ID - allow USER and ADMIN
    Flight::route('GET /order/@orderID', function ($orderID) {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::orders_details_service()->get_order_details_by_order_id($orderID));
    });

    // Add new order details - only ADMIN
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::orders_details_service()->add_order_details($data);
            echo json_encode(['message' => 'Order details added successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Update existing order details - only ADMIN
    Flight::route('PUT /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::orders_details_service()->update_order_details($data);
            echo json_encode(['message' => 'Order details updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Delete order details - only ADMIN
    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        try {
            Flight::orders_details_service()->delete_order_details($id);
            echo json_encode(['message' => 'Order details deleted successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

});
