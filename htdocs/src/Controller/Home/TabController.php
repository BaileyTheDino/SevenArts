<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Service\AuthChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TabController extends AbstractController
{
    private const array TAB_LIST = [
        [
            'name' => 'Eddy Schellekens',
            'amount' => '€50.70',
            'imageName' => 'images/tab/face-1.svg'
        ],
        [
            'name' => 'Bailey Lievens',
            'amount' => '€5.70',
            'imageName' => 'images/tab/face-2.svg'
        ],
        [
            'name' => 'Table 24',
            'amount' => '€50.70',
            'imageName' => 'images/tab/face-3.svg'
        ],
        [
            'name' => 'Eddy Schellekens',
            'amount' => '€50.70',
            'imageName' => 'images/tab/face-4.svg'
        ],
        [
            'name' => 'Bailey Lievens',
            'amount' => '€5.70',
            'imageName' => 'images/tab/face-5.svg'
        ],
        [
            'name' => 'Table 24',
            'amount' => '€50.70',
            'imageName' => 'images/tab/face-6.svg'
        ],
    ];

    public function __construct(
        private readonly AuthChecker $authenticationChecker
    ) {}

    #[Route('/tab', name: 'app.tab')]
    public function tab(): Response
    {
        if (!$this->authenticationChecker->isLoggedIn()) {
            return $this->redirectToRoute('app.login');
        }

        return $this->render('home/tab.html.twig', [
            'tabList' => self::TAB_LIST,
        ]);
    }
}
