<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../services/UsersService.php';

// Get all users
Flight::route('GET /api/users', function() {
    $usersService = new UsersService();
    echo json_encode($usersService->get_all_users());
});

// Get a user by ID
Flight::route('GET /api/users/@id', function($id) {
    $usersService = new UsersService();
    echo json_encode($usersService->get_user_by_id($id));
});

// Add a new user
Flight::route('POST /api/users', function() {
    $data = Flight::request()->data->getData();
    $usersService = new UsersService();
    try {
        $usersService->add_user($data);
        echo json_encode(['message' => 'User added successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Update an existing user
Flight::route('PUT /api/users', function() {
    $data = Flight::request()->data->getData();
    $usersService = new UsersService();
    try {
        $usersService->update_user($data);
        echo json_encode(['message' => 'User updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Delete a user
Flight::route('DELETE /api/users/@id', function($id) {
    $usersService = new UsersService();
    try {
        $usersService->delete_user($id);
        echo json_encode(['message' => 'User deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});
