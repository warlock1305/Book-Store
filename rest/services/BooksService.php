<?php

require_once __DIR__ . '/../dao/BooksDao.php';
require_once __DIR__ . '/GenresService.php';

class BooksService {
    private $booksDao;
    private $genresService;

    public function __construct() {
        $this->booksDao = new BooksDao();
        $this->genresService = new GenresService();
    }

    private function validate_book($book, $is_update = false) {
        $required_fields = ['title', 'author', 'price', 'description', 'publishDate', 'genreID', 'stock'];

        foreach ($required_fields as $field) {
            if (empty($book[$field]) && !$is_update) {
                throw new Exception("Field '{$field}' is required.");
            }
        }

        if (isset($book['price']) && (!is_numeric($book['price']) || $book['price'] < 0)) {
            throw new Exception("Price must be a non-negative number.");
        }

        if (isset($book['stock']) && (!is_numeric($book['stock']) || $book['stock'] < 0)) {
            throw new Exception("Stock must be a non-negative integer.");
        }

        if (isset($book['genreID'])) {
            $genre = $this->genresService->get_genre_by_id($book['genreID']);
            if (!$genre) {
                throw new Exception("Genre with ID {$book['genreID']} does not exist.");
            }
        }

        if (isset($book['publishDate']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $book['publishDate'])) {
            throw new Exception("Publish date must be in YYYY-MM-DD format.");
        }
    }

    public function get_all_books() {
        return $this->booksDao->get_all_books();
    }

    public function get_books_by_genre($genreID) {
        return $this->booksDao->get_books_by_genre($genreID);
    }

    public function get_books_by_author($author) {
        return $this->booksDao->get_books_by_author($author);
    }

    public function get_book_by_id($id) {
        return $this->booksDao->get_book_by_id($id);
    }

    public function add_book($book) {
        $this->validate_book($book);
        return $this->booksDao->insert_book($book);
    }

    public function update_book($book) {
        if (empty($book['bookID'])) {
            throw new Exception("Book ID is required for update.");
        }

        $existing = $this->booksDao->get_book_by_id($book['bookID']);
        if (!$existing) {
            throw new Exception("Book with ID {$book['bookID']} does not exist.");
        }

        $this->validate_book($book, true);
        return $this->booksDao->update_book($book);
    }

    public function delete_book($id) {
        $existing = $this->booksDao->get_book_by_id($id);
        if (!$existing) {
            throw new Exception("Book with ID $id does not exist.");
        }

        return $this->booksDao->delete_book($id);
    }
}
