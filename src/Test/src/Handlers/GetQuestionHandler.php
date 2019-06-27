<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Question\QuestionServiceInterface;

class GetQuestionHandler implements RequestHandlerInterface
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

        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $questionService = $this->container->get(QuestionServiceInterface::class);
        $data = $questionService->getQuestions($dto, $pageNumber, $itemPerPage);

        $ret = new \stdClass();
        $ret->questions = $data['questions'];
        $ret->totalPage = $data['totalDocument'] % $itemPerPage > 0 ? (int) ($data['totalDocument'] / $itemPerPage) + 1: $data['totalDocument'] / $itemPerPage ;
        $ret->pageNumber = $pageNumber;
        $ret->itemPerPage = $itemPerPage;
        
        return \Infrastructure\CommonFunction::buildResponseFormat(true, [], $ret);
    }
}
