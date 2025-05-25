<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../services/BooksService.php';
// Get all books
Flight::route('GET /api/books', function() {
    $booksService = new BooksService();
    echo json_encode($booksService->get_all_books());
});

// Get a book by ID
Flight::route('GET /api/books/@id', function($id) {
    $booksService = new BooksService();
    echo json_encode($booksService->get_book_by_id($id));
});

// Get books by genre
Flight::route('GET /api/books/genre/@genreID', function($genreID) {
    $booksService = new BooksService();
    echo json_encode($booksService->get_books_by_genre($genreID));
});

// Get books by author
Flight::route('GET /api/books/author/@author', function($author) {
    $booksService = new BooksService();
    echo json_encode($booksService->get_books_by_author($author));
});

// Add a new book
Flight::route('POST /api/books', function() {
    $data = Flight::request()->data->getData();
    $booksService = new BooksService();
    try {
        $booksService->add_book($data);
        echo json_encode(['message' => 'Book added successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Update an existing book
Flight::route('PUT /api/books', function() {
    $data = Flight::request()->data->getData();
    $booksService = new BooksService();
    try {
        $booksService->update_book($data);
        echo json_encode(['message' => 'Book updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Delete a book
Flight::route('DELETE /api/books/@id', function($id) {
    $booksService = new BooksService();
    try {
        $booksService->delete_book($id);
        echo json_encode(['message' => 'Book deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});
