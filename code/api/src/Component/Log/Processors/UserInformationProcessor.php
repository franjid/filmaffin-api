<?php

namespace App\Component\Log\Processors;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserInformationProcessor
{
    private TokenStorageInterface $tokenStorage;
    private RequestStack $requestStack;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    public function processRecord(array $records): array
    {
        $token = $this->tokenStorage->getToken();

        if ($token) {
            $records['extra']['user_id'] = $token->getUser()->getUserId();
        }

        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($currentRequest) {
            $records['extra']['client_ip'] = $currentRequest->getClientIp();
        }

        return $records;
    }
}
