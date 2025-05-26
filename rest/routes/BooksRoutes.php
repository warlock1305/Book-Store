<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../data/Roles.php';

Flight::group('/api/books', function () {

    // Get all books - allow USER and ADMIN
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::books_service()->get_all_books());
    });

    // Get a book by ID - allow USER and ADMIN
    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::books_service()->get_book_by_id($id));
    });

    // Get books by genre - allow USER and ADMIN
    Flight::route('GET /genre/@genreID', function ($genreID) {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::books_service()->get_books_by_genre($genreID));
    });

    // Get books by author - allow USER and ADMIN
    Flight::route('GET /author/@author', function ($author) {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo json_encode(Flight::books_service()->get_books_by_author($author));
    });

    // Add a new book - only ADMIN
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::books_service()->add_book($data);
            echo json_encode(['message' => 'Book added successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Update an existing book - only ADMIN
    Flight::route('PUT /', function () {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        $data = Flight::request()->data->getData();
        try {
            Flight::books_service()->update_book($data);
            echo json_encode(['message' => 'Book updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

    // Delete a book - only ADMIN
    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
        try {
            Flight::books_service()->delete_book($id);
            echo json_encode(['message' => 'Book deleted successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    });

});
