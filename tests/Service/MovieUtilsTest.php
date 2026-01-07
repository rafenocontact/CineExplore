<?php

namespace App\Tests\Service;

use App\Service\MovieUtils;
use PHPUnit\Framework\TestCase;

class MovieUtilsTest extends TestCase
{
    public function testMovieMapper()
    {
        // Simulated data from the API
        $movieFromApi = [
            'id' => 12428,
            'original_title' => 'Inception',
            'release_date' => '2010-07-16',
            'production_companies' => [
                ['name' => 'Warner Bros.'],
                ['name' => 'Legendary Entertainment'],
            ],
            'overview' => 'A mind-bending thriller about dreams within dreams.',
            'vote_average' => 8.8,
            'vote_count' => 2500,
            'poster_path' => '/kqjL17yufvn9OVhXdp8P5R7tD1vU.jpg',
            'original_language' => 'en',
        ];

        // Simulated data for video details
        $detailVideo = [
            'results' => [
                ['type' => 'Trailer','key' => 'YoHD9XEInc0'],
            ]
        ];

        // Call the movieMapper method
        $movie = MovieUtils::movieMapper($movieFromApi, $detailVideo);

        // Assertions to check if the object returned has the expected values

        // Check that the movie ID is correctly mapped
        $this->assertEquals(12428, $movie->getId());

        // Check that the title is correctly mapped
        $this->assertEquals('Inception', $movie->getTitle());

        // Check that the description is correctly mapped
        $this->assertEquals('A mind-bending thriller about dreams within dreams.', $movie->getDescription());

        // Check that the release date is correctly mapped
        $this->assertInstanceOf(\DateTime::class, $movie->getReleaseDate());
        $this->assertEquals('2010-07-16', $movie->getReleaseDate()->format('Y-m-d'));

        // Check that the production companies' names are concatenated correctly
        $this->assertEquals('Warner Bros.,Legendary Entertainment', $movie->getProductionName());

        // Check that the vote average is correctly mapped
        $this->assertEquals(8.8, $movie->getVoteAverage());

        // Check that the vote count is correctly mapped
        $this->assertEquals(2500, $movie->getVoteCount());

        // Check that the trailer video URL is correctly generated
        $this->assertEquals(MovieUtils::getUrlTrailerMovie($detailVideo), $movie->getUrlVideo());

        // Check that the thumbnail image URL is correctly generated
        $this->assertEquals('https://image.tmdb.org/t/p/original/kqjL17yufvn9OVhXdp8P5R7tD1vU.jpg', $movie->getThumbnail());

        // Check that the original language is correctly mapped
        $this->assertEquals('en', $movie->getOriginalLanguage());
    }

    public function testGetUrlTrailerMovie()
    {
        $detailMovieVideo = [
            'results' => [
                ['type' => 'Trailer', 'key' => 'YoHD9XEInc0'],
                ['type' => 'Clip', 'key' => 'R2n_TZgmmPo'],
            ]
        ];

        $url = MovieUtils::getUrlTrailerMovie($detailMovieVideo);
        $this->assertEquals('https://www.youtube.com/embed/YoHD9XEInc0', $url);
        $this->assertNotEquals('https://www.youtube.com/embed/R2n_TZgmmPo', $url);
        $this->assertEmpty(MovieUtils::getUrlTrailerMovie([]));
    }
}