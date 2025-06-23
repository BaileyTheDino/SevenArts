<?php

declare(strict_types=1);

namespace App\Controller\Home;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app.logout')]
    public function logout(): RedirectResponse
    {
        $response = $this->redirectToRoute('app.login');

        $cookie = Cookie::create('loggedIn')
            ->withExpires(new DateTime('-1 hour'))
            ->withPath('/')
            ->withSameSite(Cookie::SAMESITE_STRICT);

        $response->headers->setCookie($cookie);

        return $response;

    }
}
