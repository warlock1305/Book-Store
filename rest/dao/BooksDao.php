<?php

require_once __DIR__ . '/BaseDao.php';

class BooksDao extends BaseDao {
    public function __construct() {
        parent::__construct("books");
    }

    public function get_all_books() {
        return $this->query("SELECT * FROM books", []);
    }

    public function get_books_by_genre($genreID) {
        return $this->query("SELECT * FROM books WHERE genreID = :genreID", [
            "genreID" => $genreID
        ]);
    }

    public function get_books_by_author($author) {
        return $this->query("SELECT * FROM books WHERE author = :author", [
            "author" => $author
        ]);
    }
    
    public function get_book_by_id($id) {
        return $this->query_unique("SELECT * FROM books WHERE bookID = :id", [
            "id" => $id
        ]);
    }

    public function insert_book($book) {
        try {
            $query = "INSERT INTO books (bookID, title, author, price, description, publishDate, genreID, stock, image)
                      VALUES (:bookID, :title, :author, :price, :description, :publishDate, :genreID, :stock, :image)";
            $params = [
                ':bookID' => $book['bookID'],
                ':title' => $book['title'],
                ':author' => $book['author'],
                ':price' => $book['price'],
                ':description' => $book['description'],
                ':publishDate' => $book['publishDate'],
                ':genreID' => $book['genreID'],
                ':stock' => $book['stock'],
                ':image' => $book['image']
            ];
            $this->execute($query, $params);

            return [
                "message" => "Book inserted successfully",
                "bookID" => $book['bookID']
            ];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to insert book",
                "details" => $e->getMessage()
            ];
        }
    }

    public function update_book($book) {
        try {
            $query = "UPDATE books SET 
                        title = :title,
                        author = :author,
                        price = :price,
                        description = :description,
                        publishDate = :publishDate,
                        genreID = :genreID,
                        stock = :stock,
                        image = :image
                      WHERE bookID = :bookID";

            $params = [
                ':bookID' => $book['bookID'],
                ':title' => $book['title'],
                ':author' => $book['author'],
                ':price' => $book['price'],
                ':description' => $book['description'],
                ':publishDate' => $book['publishDate'],
                ':genreID' => $book['genreID'],
                ':stock' => $book['stock'],
                ':image' => $book['image']
            ];

            $this->execute($query, $params);

            return ["message" => "Book updated successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to update book",
                "details" => $e->getMessage()
            ];
        }
    }

    public function delete_book($id) {
        try {
            $this->execute("DELETE FROM books WHERE bookID = :id", ["id" => $id]);
            return ["message" => "Book deleted successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to delete book",
                "details" => $e->getMessage()
            ];
        }
    }
}
