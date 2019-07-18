<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Interfaces\ExportServiceInterface;

class ExportCandidateResultHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $examService = $this->container->get(ExportServiceInterface::class);
        $ok = $examService->exportCandidateExamResult($dto, $messages, $writer, $candiateName);
        if (!$ok) {
            return \Infrastructure\CommonFunction::buildResponseFormat($ok, $messages);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$candiateName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        die;
    }
}
