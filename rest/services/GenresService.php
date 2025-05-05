<?php

require_once __DIR__ . '/../dao/GenresDao.php';

class GenresService {
    private $genresDao;

    public function __construct() {
        $this->genresDao = new GenresDao();
    }

    private function validate_genre($genre, $is_update = false) {
        if (!$is_update && empty($genre['name'])) {
            throw new Exception("Genre name is required.");
        }

        if (isset($genre['name']) && strlen(trim($genre['name'])) < 2) {
            throw new Exception("Genre name must be at least 2 characters long.");
        }

        if (!$is_update && $this->genresDao->get_genre_by_name($genre['name'])) {
            throw new Exception("Genre '{$genre['name']}' already exists.");
        }
    }

    public function get_all_genres() {
        return $this->genresDao->get_all_genres();
    }

    public function get_genre_by_id($id) {
        $genre = $this->genresDao->get_genre_by_id($id);
        if (!$genre) {
            throw new Exception("Genre with ID $id not found.");
        }
        return $genre;
    }

    public function get_genre_by_name($name) {
        return $this->genresDao->get_genre_by_name($name);
    }

    public function add_genre($genre) {
        $this->validate_genre($genre);
        return $this->genresDao->insert_genre($genre);
    }

    public function update_genre($genre) {
        if (empty($genre['genreID'])) {
            throw new Exception("Genre ID is required for update.");
        }

        $existing = $this->genresDao->get_genre_by_id($genre['genreID']);
        if (!$existing) {
            throw new Exception("Genre with ID {$genre['genreID']} does not exist.");
        }

        $this->validate_genre($genre, true);
        return $this->genresDao->update_genre($genre);
    }

    public function delete_genre($id) {
        $existing = $this->genresDao->get_genre_by_id($id);
        if (!$existing) {
            throw new Exception("Genre with ID $id does not exist.");
        }

        return $this->genresDao->delete_genre($id);
    }
}
