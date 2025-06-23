<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Service\AuthChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    private const array PRODUCT_LIST = [
        'softdrinks' => [
            [
                'name' => 'Coca Cola',
                'amount' => '25cl',
                'value' => '€2.20',
                'imageName' => 'images/products/coca-cola-25cl.png'
            ],
            [
                'name' => 'Pimento',
                'amount' => '25cl',
                'value' => '€2.40',
                'imageName' => 'images/products/pimento-25cl.png'
            ],
        ],
        'snacks' => [
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
            [
                'name' => 'Zoute chips',
                'amount' => '40gr',
                'value' => '€2.20',
                'imageName' => 'images/products/zoute-chips-40gr.png'
            ],
        ],
    ];

    public function __construct(
        private readonly AuthChecker $authenticationChecker
    ) {}

    #[Route('/order', name: 'app.order')]
    public function order(): Response
    {
        if (!$this->authenticationChecker->isLoggedIn()) {
            return $this->redirectToRoute('app.login');
        }

        return $this->render('home/order.html.twig', [
            'productList' => self::PRODUCT_LIST,
        ]);
    }
}
