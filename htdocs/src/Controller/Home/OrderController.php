<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Service\AuthChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    public function __construct(
        private readonly AuthChecker $authenticationChecker
    ) {}

    #[Route('/order', name: 'app.order')]
    public function order(): Response
    {
        if (!$this->authenticationChecker->isLoggedIn()) {
            return $this->redirectToRoute('app.login');
        }

        return $this->render('home/order.html.twig');
    }
}
