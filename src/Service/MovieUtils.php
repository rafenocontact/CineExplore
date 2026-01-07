<?php


namespace App\Service;

use App\Entity\Movie;
use Doctrine\Common\Collections\ArrayCollection;


class MovieUtils
{
    public static function languageCode(string $language): string
    {
        return match ($language) {
            "english" => "en-EN",
            default => "fr-FR"
        };
    }

    /**
     * @param array $movieFromApi
     * @param array $detailVideo
     * @return Movie
     */
    public static function movieMapper(array $movieFromApi, array $detailVideo):Movie
    {
        // id of movie
        $id = $movieFromApi['id'] ?? 0;

        //Title of movie
        $title = $movieFromApi['original_title'] ?? "";

        //Release date
        $releaseDate = isset($movieFromApi['release_date']) ? new \DateTime($movieFromApi['release_date']) : null;

        //get production companies for getting production name
        $productionCompanies = $movieFromApi['production_companies'] ?? [];
        $productionName = implode(',',
            array_unique(
                array_map(fn($company) => $company['name'], $productionCompanies)
            )
        );

        //Description
        $description = $movieFromApi['overview'] ?? "";

        //Vote average
        $voteAverage = $movieFromApi['vote_average'] ?? 0;

        //Vote account
        $voteCount = $movieFromApi['vote_count'] ?? 0;

        //Get video url trailer
        $urlVideo = MovieUtils::getUrlTrailerMovie($detailVideo);

        //Get image for minature
        $thumbnail = "https://image.tmdb.org/t/p/original/" . ltrim($movieFromApi['poster_path'], '/');

        //Get original language
        $originalLanguage = $movieFromApi['original_language'] ?? 0;

        //Create object movie
        return self::createMovie($id, $title, $description, $releaseDate, $productionName, $voteAverage, $voteCount,
            $urlVideo, $thumbnail, $originalLanguage);

    }

    /**
     * @param ArrayCollection $moviesFromApi
     * @return array
     */
    public static function sortMoviesByVoteAverage(ArrayCollection $moviesFromApi): array
    {
        // Sort collection by vote average of this movie
        $iterator = $moviesFromApi->getIterator();
        $iterator->uasort(function ($first, $second) {
                return $second->getVoteAverage() <=> $first->getVoteAverage();
            }
        );

        return array_values(iterator_to_array($iterator));
    }

    /**
     * @param array $listMovies
     * @return Movie
     */
    public static function getTopMovie(array $listMovies): Movie
    {
        return $listMovies[array_key_first($listMovies)];
    }

    /**
     * @param array|null $detailMovieDetail
     */
    public static function getUrlTrailerMovie(?array $detailMovieDetail):string
    {
        $url = "";
        if(is_array($detailMovieDetail) && isset($detailMovieDetail['results']) && count($detailMovieDetail['results']) > 0) {
            foreach ($detailMovieDetail['results'] as $detail) {
                if ($detail['type'] == 'Trailer') {
                    $url = "https://www.youtube.com/embed/" . $detail['key'];
                    break;
                }
            }
        }

        return $url;
    }

    private static function createMovie(
        int $id,
        string $title,
        string $description,
        \DateTime $releaseDate,
        string $productionName,
        float $voteAverage,
        int $voteCount,
        string $urlVideo,
        string $thumbnail,
        string $originalLanguage
    ): Movie
    {
        $movie = new Movie();
        $movie->setId($id);
        $movie->setTitle($title);
        $movie->setDescription($description);
        $movie->setReleaseDate($releaseDate);
        $movie->setProductionName($productionName);
        $movie->setVoteAverage($voteAverage);
        $movie->setVoteCount($voteCount);
        $movie->setUrlVideo($urlVideo);
        $movie->setThumbnail($thumbnail);
        $movie->setOriginalLanguage($originalLanguage);

        return $movie;
    }
}