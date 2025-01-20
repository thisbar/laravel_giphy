<?php

namespace Database\Seeders;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;
use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\Password;
use LaravelGhipy\Core\Users\Domain\User;

class UserSeeder extends Seeder
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function run(): void
    {
        $user = User::create(Email::from('test@test.com'), Password::from('password123'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
