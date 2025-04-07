<?php

require_once __DIR__ . '/rest/dao/GenresDao.php';

$genresDao = new GenresDao();

$testGenre = [
    "genreID" => "G002",
    "genreName" => "Science Fiction"
];

$insertResult = $genresDao->insert_genre($testGenre);
echo "Insert:\n";
print_r($insertResult);

$allGenres = $genresDao->get_all_genres();
echo "\nAll Genres:\n";
print_r($allGenres);

$singleGenre = $genresDao->get_genre_by_id("G002");
echo "\nSingle Genre by ID 'G002':\n";
print_r($singleGenre);

$updateResult = $genresDao->update_genre([
    "genreID" => "G002",
    "genreName" => "Sci-Fi"
]);
echo "\nUpdate:\n";
print_r($updateResult);

$deleteResult = $genresDao->delete_genre("G002");
echo "\nDelete:\n";
print_r($deleteResult);
