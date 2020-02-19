<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     * @param EntityManagerInterface $manager
     * @param StatsService $statsService
     * @return Response
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        $stats = $statsService->getStats();
        $bestAds = $statsService->getAdsStats('ASC');
        $worstAds = $statsService->getAdsStats('DESC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
