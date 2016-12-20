<?php

namespace Component\Log\Processors;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserInformationProcessor
{
    /** @var TokenStorageInterface $tokenStorage */
    private $tokenStorage;

    /** @var  RequestStack $requestStack */
    private $requestStack;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    /**
     * @param array $records
     * @return array
     */
    public function processRecord(array $records)
    {
        $token = $this->tokenStorage->getToken();

        if ($token)
        {
            $records['extra']['user_id'] = $token->getUser()->getUserId();
        }

        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($currentRequest)
        {
            $records['extra']['client_ip'] = $currentRequest->getClientIp();
        }

        return $records;
    }
}
