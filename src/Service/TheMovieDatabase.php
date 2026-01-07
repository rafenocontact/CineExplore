<?php


namespace App\Service;


use App\Entity\GenreMovie;
use App\Entity\Movie;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TheMovieDatabase implements MovieInterface
{
    public const API_ALL_GENRE = '/genre/movie/list';
    public const API_MOVIE_BY_GENRE  = '/discover/movie';
    public const API_SEARCH_MOVIE  = '/search/movie';
    public const URL_MOVIE_DETAIL = '/movie/[id_movie]';
    public const URL_VIDEO_MOVIE_DETAIL = '/movie/[id_movie]/videos';

    private string $langageCode;

    public function __construct(
        private HttpClientInterface $client,
        private string $urlApiTmdb,
        private string $tmdbLanguage,
        private string $apiKeyTmdb,
    )
    {
        $this->langageCode = MovieUtils::languageCode($this->tmdbLanguage);
    }

    /**
     * @param string $urlEndPoint
     * @param array $criteria
     * @param string $method
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function requestToApi(string $urlEndPoint, array $criteria = [], string $method = 'GET'): array
    {
        $urlEndPoint = $this->urlApiTmdb.$urlEndPoint;
        $criteria = [
            'query' => array_merge($criteria, ['api_key' => $this->apiKeyTmdb])
        ];

        return $this->client->request($method, $urlEndPoint, $criteria)->toArray();
    }

    /**
     * @return array
     */
    public function getAllGenreMovie(): array
    {
        // Get all genre existing for movies to API TMDB
        $response = $this->requestToApi(self::API_ALL_GENRE);

        $genresCollection = new ArrayCollection();
        foreach ($response['genres'] as $genres) {
            $genresCollection->add(new GenreMovie($genres['id'], $genres['name']));
        }

        return $genresCollection->toArray();
    }

    /**
     * @param int|null $idGenre
     * @return array
     */
    public function getMoviesByGenre(?int $idGenre): array
    {
        //language argument
        $criteria['language'] = $this->langageCode;

        //Find genre if exist
        if (!is_null($idGenre)) {
            $criteria['with_genres'] = $idGenre;
        }

        //Sort result by vote popularity desc
        $criteria['sort_by'] = "popularity.desc";

        //Sort with movie which has video
        $criteria['include_video'] = true;

        // Get list of movies by genre to API TMDB
        $response = $this->requestToApi(self::API_MOVIE_BY_GENRE, $criteria);

        $moviesList = new ArrayCollection();

        foreach ($response['results'] as $movie) {
            //get video detail for each movie
            $moviesList->add($this->getMovieDetails($movie['id']));
        }

        // We return this collection sort by vote average
        return MovieUtils::sortMoviesByVoteAverage($moviesList);
    }

    /**
     * @param string|null $movieToFind
     * @return array
     */
    public function findMovie(?string $movieToFind): array
    {
        //language argument
        $criteria['language'] = $this->langageCode;

        //find the movie if search text exist
        if (!is_null($movieToFind)) {
            $criteria['query'] = $movieToFind;
        }

        // Get list of movies by genre to API TMDB
        $response = $this->requestToApi(self::API_SEARCH_MOVIE, $criteria);

        $moviesList = new ArrayCollection();

        foreach ($response['results'] as $movie) {
            //get video detail for each movie
            $moviesList->add($this->getMovieDetails($movie['id']));
        }

        // We return this collection sort by vote average
        return MovieUtils::sortMoviesByVoteAverage($moviesList);
    }

    /**
     * @param int $idMovie
     * @return Movie
     */
    public function getMovieDetails(int $idMovie):Movie
    {
        //language argument
        $criteria['language'] = $this->langageCode;

        // Get movie detail to API TMDB
        $movieFromApi = $this->requestToApi(str_replace('[id_movie]', $idMovie, self::URL_MOVIE_DETAIL), $criteria);
        $detailVideo = $this->getVideoMovieDetails($idMovie);

        return MovieUtils::movieMapper($movieFromApi, $detailVideo);
    }

    /**
     * @param int $idMovie
     * @return array
     */
    public function getVideoMovieDetails(int $idMovie): array
    {
        //language argument
        $criteria['language'] = $this->langageCode;

        // Get movie detail to API TMDB
        $movieFromApi = $this->requestToApi(str_replace('[id_movie]', $idMovie, self::URL_VIDEO_MOVIE_DETAIL), $criteria);

        //when no result, change language to default language (english) and retry request
        if(count($movieFromApi['results']) < 1) {
            //language argument
            $criteria['language'] = MovieUtils::languageCode('english');

            // Get movie detail to API TMDB
            $movieFromApi = $this->requestToApi(str_replace('[id_movie]', $idMovie, self::URL_VIDEO_MOVIE_DETAIL), $criteria);
        }

        return $movieFromApi;
    }
}