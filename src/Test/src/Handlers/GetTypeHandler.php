<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Interfaces\TypeServiceInterface;

class GetTypeHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $queryData = $request->getQueryParams();
        $pageNumber = isset($queryData['pageNumber']) ? $queryData['pageNumber'] : 1;
        $itemPerPage = isset($queryData['itemPerPage']) ? $queryData['itemPerPage'] : 25;
        $content = isset($queryData['content']) ? $queryData['content'] : '';

        $typeService = $this->container->get(TypeServiceInterface::class);
        $data = $typeService->getTypes($content, $messages, $pageNumber, $itemPerPage);

        return \Infrastructure\CommonFunction::buildResponseFormat(true, [], $data);        
    }
}