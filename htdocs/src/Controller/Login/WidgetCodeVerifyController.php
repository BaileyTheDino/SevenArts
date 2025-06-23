<?php

declare(strict_types=1);

namespace App\Controller\Login;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class WidgetCodeVerifyController extends AbstractController
{
    public function __construct(
        #[Autowire('%env(string:LOGIN_CODE)%')]
        private readonly string $code,
    )
    {
    }

    #[Route('/widgets/login/verify-code', name: 'app.widget.verifyCode')]
    public function verifyCode(Request $request): JsonResponse
    {
        /** @var array<string, mixed> $data */
        $data = json_decode($request->getContent(), true);
        $code = $data['code'] ?? '';

        if ($code === $this->code) {
            $cookie = Cookie::create('loggedIn', 'true')
                ->withSameSite(Cookie::SAMESITE_STRICT)
                ->withExpires((new DateTime())->modify('+1 hour'));

            $response = new JsonResponse(['isValid' => true]);
            $response->headers->setCookie($cookie);
            return $response;
        }

        return new JsonResponse(['isValid' => false]);
    }
}
