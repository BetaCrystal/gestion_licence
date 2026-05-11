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
            ],
            [
                'email' => 'j.martins@mentalworks.fr',
                'firstName' => 'Jeff',
                'lastName' => 'Martins-Jacquelot',
                'roles' => ['ROLE_USER'],
                'password' => 'louvre123'
            ],
            [
                'email' => 'o.salesse@mentalworks.fr',
                'firstName' => 'Olivier',
                'lastName' => 'Salesse',
                'roles' => ['ROLE_USER'],
                'password' => 'password1234'
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::data() as $index => $data) {

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

            $this->addReference('user_' . ($index + 1), $user);
        }

        $manager->flush();
    }
}
