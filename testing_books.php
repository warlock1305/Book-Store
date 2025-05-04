<?php

require_once __DIR__ . '/rest/dao/BooksDao.php';

$booksDao = new BooksDao();

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

$insertResult = $booksDao->insert_book($testBook);
echo "Insert:\n";
print_r($insertResult);

$allBooks = $booksDao->get_all_books();
echo "\nAll Books:\n";
print_r($allBooks);

$singleBook = $booksDao->get_book_by_id("B001");
echo "\nSingle Book:\n";
print_r($singleBook);
