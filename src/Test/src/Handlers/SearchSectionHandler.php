<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class SearchSectionHandler implements RequestHandlerInterface
{
    /** @var string */
    private $containerName;

    /** @var Router\RouterInterface */
    private $router;

    /** @var Psr\Container\ContainerInterface */
    private $container;

    
    public function __construct(
        string $containerName,
        ContainerInterface $container,
        Router\RouterInterface $router
    ) {
        $this->containerName = $containerName;
        $this->container = $container;
        $this->router        = $router;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dm =  $this->container->get('documentManager');        
        
        return new JsonResponse([
            'welcome' => 'Search section handler.',
        ]);
        
    }
}
