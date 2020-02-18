<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::Create('fr-FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        // Pour Gérer les utilisateurs...personnel ayant le rôle administrateur
        $adminUser = new User();
        $adminUser->setFirstName('Lord')
            ->setLastName('Eliseus')
            ->setEmail('eliseus456@gmail.com')
            ->setPicture('http://placehold.it/200x100')
            ->setIntroduction($faker->sentence)
            ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(3)).'</p>')
            ->setHash($this->passwordEncoder->encodePassword($adminUser, 'password'))
            ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // Pour gérer les utilisateurs
        $users = [];
        $genres = ['male', 'female'];
        for ($i= 1; $i<=10; $i++){
            $user = new User();
            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99).'jpg';
            $picture .= ($genre == 'male' ? 'men/' : '/women').$pictureId;
            $hash = $this->passwordEncoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(3)).'</p>')
                ->setHash($hash)
                ->setPicture($picture);
            $manager->persist($user);
            $users[] = $user;
        }

        // Pour gérer les annonces
        for ($i = 1; $i<=30; $i++){
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>', $faker->paragraphs(5)). '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(2000, 4000))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);

            // Por gérer les images
            for ($j = 1; $j<= mt_rand(2, 5); $j++){
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

            for ($j=1; $j <= mt_rand(0,10); $j++){
                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                $duration = mt_rand(3, 10);
                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment)
                ;
                $manager->persist($booking);

                // Gestion des commentaires
                if(mt_rand(0, 1)){
                    $comment = new Comment();
                    $comment->setContent($faker->paragraph())
                        ->setRating(mt_rand(1, 5))
                        ->setAuthor($booker)
                        ->setAd($ad);
                    $manager->persist($comment);
                }
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
