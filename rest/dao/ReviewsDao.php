<?php

require_once __DIR__ . '/BaseDao.php';

class ReviewsDao extends BaseDao {
    public function __construct() {
        parent::__construct("reviews");
    }

    public function get_all_reviews() {
        return $this->query("SELECT * FROM reviews", []);
    }

    public function get_reviews_by_book($bookID) {
        return $this->query("SELECT * FROM reviews WHERE bookID = :bookID", [
            "bookID" => $bookID
        ]);
    }

    public function get_review_by_id($reviewID) {
        return $this->query_unique("SELECT * FROM reviews WHERE reviewID = :reviewID", [
            "reviewID" => $reviewID
        ]);
    }

    public function insert_review($review) {
        try {
            $query = "INSERT INTO reviews (reviewID, bookID, userID, reviewDate, rating, comment) 
                      VALUES (:reviewID, :bookID, :userID, :reviewDate, :rating, :comment)";
            $params = [
                ':reviewID' => $review['reviewID'],
                ':bookID' => $review['bookID'],
                ':userID' => $review['userID'],
                ':reviewDate' => $review['reviewDate'],
                ':rating' => $review['rating'],
                ':comment' => $review['comment']
            ];
            $this->execute($query, $params);

            return [
                "message" => "Review inserted successfully",
                "reviewID" => $review['reviewID']
            ];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to insert review",
                "details" => $e->getMessage()
            ];
        }
    }

    public function update_review($review) {
        try {
            $query = "UPDATE reviews SET 
                        rating = :rating,
                        comment = :comment
                      WHERE reviewID = :reviewID";

            $params = [
                ':reviewID' => $review['reviewID'],
                ':rating' => $review['rating'],
                ':comment' => $review['comment']
            ];

            $this->execute($query, $params);

            return ["message" => "Review updated successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to update review",
                "details" => $e->getMessage()
            ];
        }
    }

    public function delete_review($reviewID) {
        try {
            $this->execute("DELETE FROM reviews WHERE reviewID = :reviewID", ["reviewID" => $reviewID]);
            return ["message" => "Review deleted successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to delete review",
                "details" => $e->getMessage()
            ];
        }
    }
}
