<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     * @param AdRepository $adRepository
     * @param int $page
     * @param Pagination $pagination
     * @return Response
     */
    public function index(AdRepository $adRepository, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Ad::class)
            ->setCurrentPage($page)
        ;
        return $this->render('admin/ads/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash('success',
                "La modification a été prise en compte");
        }

        return $this->render('admin/ads/edit.html.twig',[
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/ads/{id}/delete", name="admin_ads_delete")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function delete(Ad $ad, Request $request, EntityManagerInterface $manager){
        if (count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède des réservations"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash('success',
                "L'annonce <strong> {$ad->getTitle()} </strong> a été bien supprimée");
        }
            return $this->redirectToRoute('admin_ads_index');
    }

}
