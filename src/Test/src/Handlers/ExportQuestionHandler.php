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

        $content = isset($queryData['content']) ? $queryData['content'] : '';
        $type = isset($queryData['type']) ? $queryData['type'] : '';

        $dto = new \stdClass();
        $dto->type = $type;
        $dto->content = $content;

        $exportService = $this->container->get(ExportServiceInterface::class);
        $fileName = $exportService->exportQuestion($dto, $pageNumber, $itemPerPage);
        
        $nameOnly = \basename($fileName);
        header("Content-type: application/zip"); 
        header("Content-Disposition: attachment; filename=$nameOnly");
        header("Content-length: " . filesize($fileName));
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        readfile("$fileName");

        return \Infrastructure\CommonFunction::buildResponseFormat(true, []);
    }
}
