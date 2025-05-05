<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../services/GenresService.php';

// Get all genres
Flight::route('GET /api/genres', function() {
    $genresService = new GenresService();
    echo json_encode($genresService->get_all_genres());
});

// Get a genre by ID
Flight::route('GET /api/genres/@id', function($id) {
    $genresService = new GenresService();
    echo json_encode($genresService->get_genre_by_id($id));
});

// Add a new genre
Flight::route('POST /api/genres', function() {
    $data = Flight::request()->data->getData();
    $genresService = new GenresService();
    try {
        $genresService->add_genre($data);
        echo json_encode(['message' => 'Genre added successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Update an existing genre
Flight::route('PUT /api/genres', function() {
    $data = Flight::request()->data->getData();
    $genresService = new GenresService();
    try {
        $genresService->update_genre($data);
        echo json_encode(['message' => 'Genre updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Delete a genre
Flight::route('DELETE /api/genres/@id', function($id) {
    $genresService = new GenresService();
    try {
        $genresService->delete_genre($id);
        echo json_encode(['message' => 'Genre deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});
