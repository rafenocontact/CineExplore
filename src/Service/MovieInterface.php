<?php

namespace App\Service;


use App\Entity\Movie;

interface MovieInterface
{
    /**
	 * @return Movie[]
	 */
	public function getAllGenreMovie(): array;

	/**
	 * @param int $idGenre
	 * @return Movie[]
	 */
	public function getMoviesByGenre(int $idGenre): array;

    public function getMovieDetails(int $idMovie): Movie;
}
