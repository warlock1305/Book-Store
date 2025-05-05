<?php

require_once __DIR__ . '/../dao/ReviewsDao.php';
require_once __DIR__ . '/../dao/BooksDao.php';
require_once __DIR__ . '/../dao/UsersDao.php';

class ReviewsService {
    private $reviewsDao;
    private $booksDao;
    private $usersDao;

    public function __construct() {
        $this->reviewsDao = new ReviewsDao();
        $this->booksDao = new BooksDao();
        $this->usersDao = new UsersDao();
    }

    private function validate_review($review, $is_update = false) {
        $required_fields = ['bookID', 'userID', 'content', 'createdAt'];

        foreach ($required_fields as $field) {
            if (empty($review[$field]) && !$is_update) {
                throw new Exception("Field '{$field}' is required.");
            }
        }

        if (!empty($review['bookID']) && !$this->booksDao->get_book_by_id($review['bookID'])) {
            throw new Exception("Book with ID {$review['bookID']} does not exist.");
        }

        if (!empty($review['userID']) && !$this->usersDao->get_user_by_id($review['userID'])) {
            throw new Exception("User with ID {$review['userID']} does not exist.");
        }

        if (!empty($review['createdAt']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $review['createdAt'])) {
            throw new Exception("Created date must be in YYYY-MM-DD format.");
        }
    }

    public function get_all_reviews() {
        return $this->reviewsDao->get_all_reviews();
    }

    public function get_reviews_by_book($bookID) {
        return $this->reviewsDao->get_reviews_by_book($bookID);
    }

    public function get_reviews_by_user($userID) {
        return $this->reviewsDao->get_reviews_by_user($userID);
    }

    public function get_review_by_id($reviewID) {
        return $this->reviewsDao->get_review_by_id($reviewID);
    }

    public function add_review($review) {
        $this->validate_review($review);
        return $this->reviewsDao->insert_review($review);
    }

    public function update_review($review) {
        if (empty($review['reviewID'])) {
            throw new Exception("Review ID is required for update.");
        }

        $existing = $this->reviewsDao->get_review_by_id($review['reviewID']);
        if (!$existing) {
            throw new Exception("Review with ID {$review['reviewID']} does not exist.");
        }

        $this->validate_review($review, true);
        return $this->reviewsDao->update_review($review);
    }

    public function delete_review($reviewID) {
        $existing = $this->reviewsDao->get_review_by_id($reviewID);
        if (!$existing) {
            throw new Exception("Review with ID $reviewID does not exist.");
        }

        return $this->reviewsDao->delete_review($reviewID);
    }
}
