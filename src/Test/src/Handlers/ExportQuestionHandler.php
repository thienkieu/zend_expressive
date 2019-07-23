<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Interfaces\ExportServiceInterface;

class ExportQuestionHandler implements RequestHandlerInterface
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
        $exportService = $this->container->get(ExportServiceInterface::class);
        $exportService->exportQuestion($dto, $pageNumber, $itemPerPage);
        die;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$candiateName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        return \Infrastructure\CommonFunction::buildResponseFormat(true, [], $ret);
    }
}
