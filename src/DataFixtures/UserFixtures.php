<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function data(): array
    {
        return [
            [
                'email' => 'stella.ribas@lyceestvincent.net',
                'firstName' => 'Stella',
                'lastName' => 'Ribas',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'password123'
            ]
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::data() as $data) {

            $user = new User();
            $user->SetFirstName($data['firstName']);
            $user->SetLastName($data['lastName']);
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $data['password']
            );

            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
