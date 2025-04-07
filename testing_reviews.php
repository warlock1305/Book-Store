<?php

require_once __DIR__ . '/rest/dao/ReviewsDao.php';
require_once __DIR__ . '/rest/dao/BooksDao.php';
require_once __DIR__ . '/rest/dao/UsersDao.php';

$reviewsDao = new ReviewsDao();
$booksDao = new BooksDao();
$usersDao = new UsersDao();

// Insert a test book
$testBook = [
    "bookID" => "B001",
    "title" => "Test Book Title",
    "author" => "Test Author",
    "price" => 29.99,
    "description" => "This is a test description.",
    "publishDate" => "2025-04-01",
    "genreID" => "G001",
    "stock" => 15,
    "image" => "uploads/test-book.jpg"
];
$booksDao->insert_book($testBook);

// Insert a test user
$testUser = [
    "userID" => "U001",
    "username" => "testuser",
    "email" => "testuser@example.com",
    "password" => "password123",
    "firstName" => "Test",
    "lastName" => "User",
    "dateJoined" => "2025-04-01",
    "address" => "123 Test Street"
];
$usersDao->insert_user($testUser);

$testReview = [
    "reviewID" => "R001",
    "bookID" => "B001",
    "userID" => "U001",
    "reviewDate" => "2025-04-07",
    "rating" => 4,
    "comment" => "Great book, highly recommend!"
];

$insertResult = $reviewsDao->insert_review($testReview);
echo "Insert:\n";
print_r($insertResult);

$allReviews = $reviewsDao->get_all_reviews();
echo "\nAll Reviews:\n";
print_r($allReviews);

$singleReview = $reviewsDao->get_review_by_id("R001");
echo "\nSingle Review by ID 'R001':\n";
print_r($singleReview);

$updateResult = $reviewsDao->update_review([
    "reviewID" => "R001",
    "rating" => 5,
    "comment" => "An amazing book, a must-read!"
]);
echo "\nUpdate:\n";
print_r($updateResult);

$deleteResult = $reviewsDao->delete_review("R001");
echo "\nDelete:\n";
print_r($deleteResult);

// Delete the test book and user
$booksDao->delete_book("B001");
$usersDao->delete_user("U001");

echo "\nTest Data Cleanup Complete!";
