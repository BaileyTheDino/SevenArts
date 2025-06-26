<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Entity\Tab;
use App\POS\POSService;
use App\Repository\TabRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WidgetTabController extends AbstractController
{
    public function __construct(
        private readonly POSService $POSService,
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

    #[Route('/widgets/tab/getTabDetails', name: 'app.widget.tab.getTabDetails')]
    public function getTabDetails(Request $request): Response
    {
        $tabId = $request->query->get('id');

        if (! $tabId) {
            return new Response('Tab ID is required', Response::HTTP_BAD_REQUEST);
        }

        return $this->render('home/ajax-content/tab-details.html.twig', [
            'tab' => $this->tabRepository->find($tabId),
        ]);
    }

    #[Route('/widgets/tab/payNow', name: 'app.widget.tab.payNow')]
    public function payNow(Request $request): JsonResponse
    {
        $tabId = json_decode($request->getContent(), true)['id'];

        $tab = $this->tabRepository->find($tabId);

        if ($tab instanceof Tab === false) {
            return new JsonResponse(['success' => false]);
        }

        $this->POSService->sendToPOS($tab->getOrderData());

        $this->tabRepository->delete($tab);

        return new JsonResponse(['success' => true]);
    }

    #[Route('/widgets/tab/delete', name: 'app.widget.tab.delete')]
    public function deleteTab(Request $request): JsonResponse
    {
        $tabId = json_decode($request->getContent(), true)['id'];

        $tab = $this->tabRepository->find($tabId);

        if ($tab instanceof Tab === false) {
            return new JsonResponse(['success' => false]);
        }

        $this->tabRepository->delete($tab);

        return new JsonResponse(['success' => true]);
    }
}
