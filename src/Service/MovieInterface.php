<?php

namespace App\Service;


interface MovieInterface
{
    public function getAllGenreMovie();

    public function getMoviesByGenre(int $idGenre);

    public function getMovieDetails(int $idMovie);
}
