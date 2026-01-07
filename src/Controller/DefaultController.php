<?php

namespace App\Controller;

use App\Service\MovieUtils;
use App\Service\TheMovieDatabase;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{

    protected const LIMIT = 5;

    public function __construct(private readonly TheMovieDatabase $theMovieDatabase)
    {}

    #[Route('/', name: 'homePage')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        //load genre movie
        $allGenre = $this->theMovieDatabase->getAllGenreMovie();

        //Get random genre
        $allGenreId= array_map(function($genre){return $genre->getId();},$allGenre);
        shuffle($allGenreId);
        $randomIdGenre = $allGenreId[array_key_first($allGenreId)];

        //get all movie by genre
        $listMovies = $this->theMovieDatabase->getMoviesByGenre($randomIdGenre);

        //get the top movie
        $topMovie = MovieUtils::getTopMovie($listMovies);

        $limit = $request->query->getInt('limit', self::LIMIT);

        if($request->isXmlHttpRequest()) {
            return $this->render('include/listMovies.html.twig', [
                'listMovies' => $this->paginatedListMovies($request, $paginator, $listMovies, $limit), // Choose the KnpPaginator pagination method
            ]);
        }

        //get list of all movie
        return $this->render('default/index.html.twig', [
            'topMovie' =>$topMovie,
            'listMovies' => $this->paginatedListMovies($request, $paginator, $listMovies, $limit),
            'limit' => $limit,
            'allGenre' => $allGenre,
            'genreSelected' => $randomIdGenre,
        ]);
    }

    #[Route(path: '/topMovie/{idGenre}', name: 'topMovie', methods: ['GET'])]
    public function getTopMovie(int $idGenre): Response
    {
        $listMovies = $this->theMovieDatabase->getMoviesByGenre($idGenre);
        $result = MovieUtils::getTopMovie($listMovies);

        return $this->render('include/topMovie.html.twig', [
            'topMovie' =>$result,
        ]);
    }

    #[Route(path: '/moviesByGenre/{idGenre}', name: 'moviesByGenre', methods: ['GET'])]
    public function getMoviesByGenre(int $idGenre, Request $request, PaginatorInterface $paginator): Response
    {
        $listMovies = $this->theMovieDatabase->getMoviesByGenre($idGenre);
        $limit = $request->query->getInt('limit', self::LIMIT);

        return $this->render('include/listMovies.html.twig', [
            'listMovies' => $this->paginatedListMovies($request, $paginator, $listMovies, $limit),
        ]);
    }

    #[Route(path: '/detailMovie/{idMovie}', name: 'detailMovie', methods: ['GET'])]
    public function getMovieDetails(int $idMovie)
    {
        $movie = $this->theMovieDatabase->getMovieDetails($idMovie);
        return $this->render('modal/detailMovie.html.twig', [
            'movie' =>$movie,
        ]);
    }

    #[Route(path: '/findMovie/{movieToFind}', name: 'movieToFind', methods: ['GET'])]
    public function findMovie(string $movieToFind, Request $request, PaginatorInterface $paginator): Response
    {
        $listMovies = $this->theMovieDatabase->findMovie($movieToFind);
        $limit = $request->query->getInt('limit', self::LIMIT);

        return $this->render('include/listMovies.html.twig', [
            'listMovies' => $this->paginatedListMovies($request, $paginator, $listMovies, $limit),
            'resultNumber'=>count($listMovies),
            'movieToFind'=>$movieToFind
        ]);
    }

    private function paginatedListMovies(Request $request, PaginatorInterface $paginator, $listMovies, $limit)
    {
        //Opter avec la methode de pagination KnpPaginator
        return $paginator->paginate(
            $listMovies,
            $request->query->getInt('page', 1),
            $limit
        );
    }
}
