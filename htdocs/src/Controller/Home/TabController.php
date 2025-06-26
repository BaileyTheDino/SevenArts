<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Entity\Tab;
use App\Repository\TabRepository;
use App\Service\AuthChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TabController extends AbstractController
{
    public function __construct(
        private readonly AuthChecker $authenticationChecker,
        private readonly TabRepository $tabRepository,
    ) {
    }

    #[Route('/tab', name: 'app.tab')]
    public function tab(): Response
    {
        if (! $this->authenticationChecker->isLoggedIn()) {
            return $this->redirectToRoute('app.login');
        }

        $tabList = $this->fetchTabList();

        return $this->render('home/tab.html.twig', [
            'tabList' => $tabList,
        ]);
    }

    /**
     * @return list<mixed>
     */
    private function fetchTabList(): array
    {
        $tabs = $this->tabRepository->findAll();

        $formattedTabs = [];
        foreach ($tabs as $tab) {
            /** @var Tab $tab */
            $formattedTabs[] = [
                'id' => $tab->getId()->toString(),
                'name' => $tab->getName(),
                'total' => sprintf('€%.2f', $tab->getPrice()),
                'imageName' => $tab->getImageUrl(),
            ];
        }

        return $formattedTabs;
    }
}
