<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function book(Ad $ad, Request $request,EntityManagerInterface $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if  ($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $booking->setBooker($user)
                ->setAd($ad);
            // Si les dates ne sont pas disponibles, erreur
            if (!$booking->isBookableDates()){
                $this->addFlash(
                    'warning',
                    "Les dates que vous avez choisi ne peuvent pas être prise en compte: elles sont déja prises."
                );
            } else {
                $manager->persist($booking);
                $manager->flush();
                return $this->redirectToRoute('booking_show',[
                    'id' => $booking->getId(),
                    'withAlert' => true
                    ]);
            }
        }

        return $this->render('booking/book.html.twig',[
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show")
     * @param Booking $booking
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function show(Booking $booking, EntityManagerInterface $manager, Request $request){
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $comment->setAd($booking->getAd())
                ->setAuthor($this->getUser());
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success',"Votre commentaire a été prise en charge");
        }
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
