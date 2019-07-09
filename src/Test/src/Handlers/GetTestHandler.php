<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Interfaces\TestServiceInterface;

class GetTestHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $queryData = $request->getQueryParams();
        $pageNumber = \Infrastructure\CommonFunction::getPageNumber($queryData, \Config\AppConstant::PageNumber);
        $itemPerPage = \Infrastructure\CommonFunction::getItemPerPage($queryData, \Config\AppConstant::ItemPerPage);
        $title = \Infrastructure\CommonFunction::getValue($queryData, 'title');
        $filterData = new \stdClass();
        $filterData->title = $title;

        $testService = $this->container->get(TestServiceInterface::class);
        $ok = $testService->getTests($tests, $messages, $filterData, $pageNumber, $itemPerPage);

        return new JsonResponse([
            'data' => $tests,
            'isSuccess' => $ok,    
            'messages' => $messages,   
        ]);
    }
}
