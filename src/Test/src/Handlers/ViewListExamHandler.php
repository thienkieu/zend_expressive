<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\ExamServiceInterface;

class ViewListExamHandler implements RequestHandlerInterface
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

        $examService = $this->container->get(ExamServiceInterface::class);
        $ret = $examService = $examService->getExams($outDTO, $messages, $pageNumber, $itemPerPage);

        return \Infrastructure\CommonFunction::buildResponseFormat($ret, $messages, $outDTO);

    }
}
