<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param AdRepository $adRepository
     * @return Response
     */
    public function index(AdRepository $adRepository)
    {

        return $this->render('home/index.html.twig', [
            'ads' => $adRepository->findBestAds(3)
        ]);
    }
}
