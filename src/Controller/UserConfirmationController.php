<?php

namespace App\Controller;

use App\Security\UserConfirmationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserConfirmationController extends AbstractController
{
    /**
     * @Route("/confirm-user/{token}", name="default_confirm_token")
     *
     * @param string $token
     * @param UserConfirmationService $userConfirmationService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(string $token, UserConfirmationService $userConfirmationService)
    {
        $userConfirmationService->confirmUser($token);
        return $this->redirectToRoute('default_index');
    }
}