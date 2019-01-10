<?php

namespace App\Security;

use App\Exception\InvalidConfirmationTokenException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UserConfirmationService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UserConfirmationService constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param string $confirmationToken
     * @throws InvalidConfirmationTokenException
     */
    public function confirmUser(string $confirmationToken)
    {
        $this->logger->debug('Fetching user by confirmation token');
        $user = $this->userRepository->findOneBy(['confirmationToken' => $confirmationToken]);

        if (!$user) {
            $this->logger->debug('User by confirmation token not found');
            throw new InvalidConfirmationTokenException();
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->entityManager->flush();
        $this->logger->debug('Confirmation token successful');
    }
}