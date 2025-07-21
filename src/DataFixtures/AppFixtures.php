<?php

namespace App\DataFixtures;

use App\Entity\Consultant;
use App\Entity\User;
use App\Entity\Member;
use App\Entity\Manager;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // === ADMIN ===
        $admin = new User();
        $admin->setEmail("admin@gmail.com")
            ->setFirstName("Super")
            ->setLastName("Admin")
            ->setPassword($this->passwordHasher->hashPassword($admin, "admin123"));
        //->setRoles(['ROLE_ADMINISTRATEUR']);
        $manager->persist($admin);

        // === MANAGER(S) ===
        $managers = [];
        for ($j = 1; $j <= 4; $j++) {
            $m = new Manager();
            $m->setEmail("manager$j@gmail.com")
                ->setFirstName("Gestion $j")
                ->setLastName("naire $j")
                ->setPassword($this->passwordHasher->hashPassword($m, "passe123"));
            //->setRoles(['ROLE_MANAGER']);
            $manager->persist($m);
            $managers[] = $m;
        }

        // === CONSULTANT ===
        $consultant = new Consultant();
        $consultant->setEmail("consultant@gmail.com")
            ->setFirstName("Consultant")
            ->setLastName("Consultant")
            ->setPassword($this->passwordHasher->hashPassword($consultant, "passe"));
        $manager->persist($consultant);


        // === Membres ===

        $nationalities = ["Gabon", "Congo Brazza", "Congo Kinshassa", "Centrafrique", "Nigeria", "Cameroon", "Togo", "Benin"];
        $quartiers = ["Ouest-Foire", "Ouakam", "POINT-E", "FANN", "LIBERTÉ 6"];

        for ($i = 1; $i <= 20; $i++) {
            $member = new Member();

            $randomNationality = $nationalities[array_rand($nationalities)];
            $randomQuartier = $quartiers[array_rand($quartiers)];

            $isMember = (bool)rand(0, 1);
            $isBaptized = (bool)rand(0, 1);
            $hasTransport = (bool)rand(0, 1);
            $isInHomeCell = (bool)rand(0, 1);

            // Dates
            $createdAt = new \DateTimeImmutable('-' . rand(0, 365) . ' days');
            $variableDate = new \DateTime('-' . rand(0, 365) . ' days');

            $member
                ->setFirstName("Nom $i")
                ->setLastName("Prénom $i")
                ->setTel("70714515$i")
                ->setQuartier($randomQuartier)
                ->setNationalite($randomNationality)
                ->setCreatedAt($createdAt)
                ->setIsMember($isMember)
                ->setMemberJoinedDate($isMember ? clone $variableDate : null)
                ->setIsBaptized($isBaptized)
                ->setBaptismDate($isBaptized ? clone $variableDate : null)
                ->setHasTransport($hasTransport)
                ->setTransportDate($hasTransport ? clone $variableDate : null)
                ->setIsInHomeCell($isInHomeCell)
                ->setHomeCellJoinDate($isInHomeCell ? clone $variableDate : null)
                ->setObservations("Observations test $i");


            // 🔁 Créé aléatoirement par l’admin ou un manager
            $random = rand(0, count($managers));
            if ($random === count($managers)) {
                $member->setCreatedBy($admin);
            } else {
                $member->setCreatedBy($managers[$random]);
            }

            $manager->persist($member);
        }

        $manager->flush();
    }
}
