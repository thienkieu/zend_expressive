<?php

declare(strict_types=1);

namespace ODMAuth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class AssignUserPermissionHandler implements RequestHandlerInterface
{
   
    /** @var Psr\Container\ContainerInterface */
    private $container;

    public function __construct(        
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {  
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);

        $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
        $ok = $authorizationService->assignUserPermission($dto, $messages);

        return \Infrastructure\CommonFunction::buildResponseFormat($ok, $messages);
        
    }
}
