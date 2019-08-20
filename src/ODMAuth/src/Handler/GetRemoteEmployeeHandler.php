<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\CandidateServiceInterface;

class GetRemoteEmployeeHandler implements RequestHandlerInterface
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
        $nameOrEmail = isset($queryData['nameOrEmail']) ? $queryData['nameOrEmail'] : '';
        $type = isset($queryData['type']) ? $queryData['type'] : '';

        $candidateService = $this->container->get(CandidateServiceInterface::class);
        $ok = $candidateService->getCandidates($candidates, $messages, $nameOrEmail, $type, $pageNumber, $itemPerPage);

        return new JsonResponse([
            'data' => $candidates,
            'isSuccess' => $ok,    
            'messages' => $messages,   
        ]);
    }
}