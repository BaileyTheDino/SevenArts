<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\AuthChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    public function __construct(
        private readonly AuthChecker $authenticationChecker,
        private readonly ProductRepository $productRepository,
    ) {
    }

    #[Route('/order', name: 'app.order')]
    public function order(): Response
    {
        if (! $this->authenticationChecker->isLoggedIn()) {
            return $this->redirectToRoute('app.login');
        }

        $productList = $this->fetchProductList();

        return $this->render('home/order.html.twig', [
            'productList' => $productList,
        ]);
    }

    /**
     * @return array<mixed>
     */
    private function fetchProductList(): array
    {
        $products = $this->productRepository->findAll();

        $groupedProducts = [];
        foreach ($products as $product) {
            if (is_string($product->getSection()) === false) {
                continue;
            }

            /** @var Product $product */
            $groupedProducts[mb_strtolower($product->getSection())][] = [
                'name' => $product->getName(),
                'amount' => $product->getQuantity(),
                'value' => sprintf('€%.2f', $product->getPrice()),
                'imageName' => $product->getImageUrl(),
            ];
        }

        return $groupedProducts;
    }
}
