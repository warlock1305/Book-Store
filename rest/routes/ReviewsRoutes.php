<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../services/ReviewsService.php';

// Get all reviews
Flight::route('GET /api/reviews', function() {
    $reviewsService = new ReviewsService();
    echo json_encode($reviewsService->get_all_reviews());
});

// Get a review by ID
Flight::route('GET /api/reviews/@id', function($id) {
    $reviewsService = new ReviewsService();
    echo json_encode($reviewsService->get_review_by_id($id));
});

// Add a new review
Flight::route('POST /api/reviews', function() {
    $data = Flight::request()->data->getData();
    $reviewsService = new ReviewsService();
    try {
        $reviewsService->add_review($data);
        echo json_encode(['message' => 'Review added successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Update an existing review
Flight::route('PUT /api/reviews', function() {
    $data = Flight::request()->data->getData();
    $reviewsService = new ReviewsService();
    try {
        $reviewsService->update_review($data);
        echo json_encode(['message' => 'Review updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// Delete a review
Flight::route('DELETE /api/reviews/@id', function($id) {
    $reviewsService = new ReviewsService();
    try {
        $reviewsService->delete_review($id);
        echo json_encode(['message' => 'Review deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
});
