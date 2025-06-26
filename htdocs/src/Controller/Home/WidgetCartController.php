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

final class WidgetCartController extends AbstractController
{
    public function __construct(
        private readonly POSService $POSService,
        private readonly TabRepository $tabRepository,
    ) {
    }

    #[Route('/widgets/cart/getContent', name: 'app.widget.cart.getContent')]
    public function getContent(Request $request): Response
    {
        /** @var array<mixed> $cart */
        $cart = json_decode($request->getContent(), true);

        return $this->render('home/ajax-content/cart-content.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/widgets/cart/payNow', name: 'app.widget.cart.payNow')]
    public function payNow(Request $request): JsonResponse
    {
        /** @var array<mixed> $cart */
        $cart = json_decode($request->getContent(), true);

        $this->POSService->sendToPOS($cart);

        return new JsonResponse(['success' => true]);
    }

    #[Route('/widgets/cart/addToTab', name: 'app.widget.cart.addToTab')]
    public function addToTab(Request $request): JsonResponse
    {
        /** @var array<mixed> $data */
        $data = json_decode($request->getContent(), true);
        $cart = $data['cart'];
        $tabName = $data['tabName'];

        $tab = $this->tabRepository->findOneBy(['name' => $tabName]);

        if ($tab instanceof Tab === false) {
            return new JsonResponse(['success' => false]);
        }

        $tab->setPrice($tab->getPrice() + $cart['total']);

        $orderData = $tab->getOrderData() ?? [];

        foreach ($cart['items'] as $item) {
            $key = $item['name'] . $item['amount'];

            /* If it's already present just add the quantities together. */
            if (array_key_exists($key, $orderData) === true) {
                $orderData[$key]['quantity'] = $orderData[$key]['quantity'] + $item['quantity'];

                continue;
            }

            $orderData[$key] = [
                'name' => $item['name'],
                'amount' => $item['amount'],
                'value' => $item['value'],
                'quantity' => $item['quantity'],
            ];
        }

        $tab->setOrderData($orderData);

        $this->tabRepository->save($tab);

        return new JsonResponse(['success' => true]);
    }
}
