<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

final readonly class AuthChecker
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    public function isLoggedIn(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if (! $request) {
            return false;
        }

        return $request->cookies->get('loggedIn') === 'true';
    }
}
