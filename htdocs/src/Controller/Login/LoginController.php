<?php

declare(strict_types=1);

namespace App\Controller\Login;

use App\Service\AuthChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    public function __construct(
        private readonly AuthChecker $authenticationChecker
    ) {}

    #[Route('/', name: 'app.login')]
    public function login(): Response
    {
        if ($this->authenticationChecker->isLoggedIn()) {
            return $this->redirectToRoute('app.order');
        }

        return $this->render('login/index.html.twig');
    }
}
