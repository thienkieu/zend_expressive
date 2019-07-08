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
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        
        $pageNumber = \Infrastructure\CommonFunction::getValue($dto, 'pageNumber', 1);
        $itemPerPage = \Infrastructure\CommonFunction::getValue($dto, 'itemPerPage', 15);

        $examService = $this->container->get(ExamServiceInterface::class);
        $ret = $examService = $examService->getExams($filterCriterial, $outDTO, $messages, $pageNumber, $itemPerPage);

        return \Infrastructure\CommonFunction::buildResponseFormat($ret, $messages, $outDTO);

    }
}
