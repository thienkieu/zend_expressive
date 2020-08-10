<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;


class ClientConfigHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $appConfig = $this->container->get('config');
        $clientConfig = $appConfig['clientConfig'];

        return new JsonResponse([
            "clientBuild" => $clientConfig['clientBuild'],
            "versionLinkNumber" => $clientConfig['versionLinkNumber'],
            "configConstants" => [
                "requestTime" => $clientConfig['requestTime'], // 10 seconds,
                "clientBuild" => $clientConfig['clientBuild'],
            ],
            "javascript" => $clientConfig['executeJavascriptOnInit'],

        ]);
    }
}
