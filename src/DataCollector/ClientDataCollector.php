<?php

namespace RM\Bundle\ClientBundle\DataCollector;

use RM\Bundle\ClientBundle\RelmsgClientBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Throwable;

class ClientDataCollector extends DataCollector
{
    public function collect(Request $request, Response $response, Throwable $exception = null): void
    {

    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return RelmsgClientBundle::NAME;
    }

    public function reset(): void
    {

    }
}
