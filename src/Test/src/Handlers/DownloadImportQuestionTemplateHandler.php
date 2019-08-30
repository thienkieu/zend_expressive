<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Interfaces\ExportServiceInterface;

class DownloadImportQuestionTemplateHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $exportService = $this->container->get(ExportServiceInterface::class);
        $ok = $exportService->exportImportQuestionTemplate($writer);
        if (!$ok) {
            return \Infrastructure\CommonFunction::buildResponseFormat($ok, $messages);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="importQuestionTemplate.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        
        die;
    }
}
