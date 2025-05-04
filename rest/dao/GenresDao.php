<?php

require_once __DIR__ . '/BaseDao.php';

class GenresDao extends BaseDao {
    public function __construct() {
        parent::__construct("genres");
    }

    public function get_all_genres() {
        return $this->query("SELECT * FROM genres", []);
    }

    public function get_genre_by_id($genreID) {
        return $this->query_unique("SELECT * FROM genres WHERE genreID = :genreID", [
            "genreID" => $genreID
        ]);
    }

    public function insert_genre($genre) {
        try {
            $query = "INSERT INTO genres (genreID, genreName) VALUES (:genreID, :genreName)";
            $params = [
                ':genreID' => $genre['genreID'],
                ':genreName' => $genre['genreName']
            ];
            $this->execute($query, $params);

            return [
                "message" => "Genre inserted successfully",
                "genreID" => $genre['genreID']
            ];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to insert genre",
                "details" => $e->getMessage()
            ];
        }
    }

    public function update_genre($genre) {
        try {
            $query = "UPDATE genres SET genreName = :genreName WHERE genreID = :genreID";

            $params = [
                ':genreID' => $genre['genreID'],
                ':genreName' => $genre['genreName']
            ];

            $this->execute($query, $params);

            return ["message" => "Genre updated successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to update genre",
                "details" => $e->getMessage()
            ];
        }
    }

    public function delete_genre($genreID) {
        try {
            $this->execute("DELETE FROM genres WHERE genreID = :genreID", ["genreID" => $genreID]);
            return ["message" => "Genre deleted successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to delete genre",
                "details" => $e->getMessage()
            ];
        }
    }
}
