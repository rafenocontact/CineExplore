<?php
namespace App\Test\Service;


use App\Service\TheMovieDatabase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TheMovieDatabaseTest extends WebTestCase
{
    protected $client;
    protected $serviceMovieDatabase;


    public function setup():void
    {
        $this->client = static::createClient();
        $this->serviceMovieDatabase = $this->client->getContainer()->get(TheMovieDatabase::class);
    }

    public function testGetAllGenreMovie():void
    {
        $this->assertIsArray($this->serviceMovieDatabase->getAllGenreMovie());
        $this->assertNotEmpty($this->serviceMovieDatabase->getAllGenreMovie());
    }

    public function testGetMoviesByGenre():void
    {
        $allGenre = $this->serviceMovieDatabase->getAllGenreMovie();
        $allGenreId= array_map(function($genre){return $genre->getId();},$allGenre);

        //get one genre
        $oneGenreId = $allGenreId[array_key_first($allGenreId)];

        $this->assertNotEmpty($this->serviceMovieDatabase->getMoviesByGenre($oneGenreId));
    }

    public function testFindMovie():void
    {
        $movieSearch = "";
        $movieWhoDoesNotExist = "nginx";
        $movieWhoExist = "superman";

        $this->assertEmpty($this->serviceMovieDatabase->findMovie($movieSearch));
        $this->assertEmpty($this->serviceMovieDatabase->findMovie($movieWhoDoesNotExist));
        $this->assertNotEmpty($this->serviceMovieDatabase->findMovie($movieWhoExist));
    }

    public function testGetMovieDetail():void
    {
        $allGenre = $this->serviceMovieDatabase->getAllGenreMovie();
        $allGenreId= array_map(function($genre){return $genre->getId();},$allGenre);
        $oneGenreId = $allGenreId[array_key_first($allGenreId)];
        $allMovie = $this->serviceMovieDatabase->getMoviesByGenre($oneGenreId);
        //get one movie
        $oneMovieId = $allMovie[array_key_first($allMovie)]->getId();

        $this->assertNotEmpty($this->serviceMovieDatabase->getMovieDetails($oneMovieId));
    }

    public function testVideoDetail():void
    {
        $allGenre = $this->serviceMovieDatabase->getAllGenreMovie();
        $allGenreId= array_map(function($genre){return $genre->getId();},$allGenre);
        $oneGenreId = $allGenreId[array_key_first($allGenreId)];
        $allMovie = $this->serviceMovieDatabase->getMoviesByGenre($oneGenreId);
        //get one movie
        $oneMovieId = $allMovie[array_key_first($allMovie)]->getId();

        $this->assertNotEmpty($this->serviceMovieDatabase->getVideoMovieDetails($oneMovieId));
    }
}