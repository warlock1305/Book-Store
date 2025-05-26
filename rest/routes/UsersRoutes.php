<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../data/Roles.php';

Flight::group('/api/users', function () {

    // Get all users - only ADMIN
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        echo json_encode(Flight::users_service()->get_all_users());
    });

    // Get a user by ID - only ADMIN
    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        echo json_encode(Flight::users_service()->get_user_by_id($id));
    });

    // Add a new user - only ADMIN
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::users_service()->add_user($data);
            echo json_encode(['message' => 'User added successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Update an existing user - only ADMIN
    Flight::route('PUT /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::users_service()->update_user($data);
            echo json_encode(['message' => 'User updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Delete a user - only ADMIN
    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        try {
            Flight::users_service()->delete_user($id);
            echo json_encode(['message' => 'User deleted successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

});
