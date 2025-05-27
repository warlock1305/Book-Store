<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../data/Roles.php';

Flight::group('/api/reviews', function () {

    // Get all reviews - allow USER and ADMIN
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::reviews_service()->get_all_reviews());
    });

    // Get a review by ID - allow USER and ADMIN
    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::reviews_service()->get_review_by_id($id));
    });

    // Add a new review - only ADMIN
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::reviews_service()->add_review($data);
            echo json_encode(['message' => 'Review added successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Update an existing review - only ADMIN
    Flight::route('PUT /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::reviews_service()->update_review($data);
            echo json_encode(['message' => 'Review updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Delete a review - only ADMIN
    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        try {
            Flight::reviews_service()->delete_review($id);
            echo json_encode(['message' => 'Review deleted successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

});
