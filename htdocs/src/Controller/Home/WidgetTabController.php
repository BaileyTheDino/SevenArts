<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Entity\Tab;
use App\Repository\TabRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WidgetTabController extends AbstractController
{
    public function __construct(
        private readonly TabRepository $tabRepository,
    ) {
    }

    #[Route('/widgets/tab/getContent', name: 'app.widget.tab.getContent')]
    public function getContent(Request $request): Response
    {
        /** @var array<string, mixed> $data */
        $data = json_decode($request->getContent(), true);

        return new JsonResponse(['test' => 'test']);
    }

    #[Route('/widgets/tab/getTabs', name: 'app.widget.tab.getTabs')]
    public function getTabs(): JsonResponse
    {
        $tabs = $this->tabRepository->findAll();

        $formattedTabs = [];
        foreach ($tabs as $tab) {
            /** @var Tab $tab */
            $formattedTabs[] = [
                'name' => $tab->getName(),
                'total' => sprintf('€%.2f', $tab->getPrice()),
                'imageName' => $tab->getImageUrl(),
            ];
        }

        return new JsonResponse($formattedTabs);
    }
}
