<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\ImportQuestionServiceInterface;

class UploadMediaHandler implements RequestHandlerInterface
{    
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        
        //$mediaService = $this->container->get(MediaServiceInterface::class);
        //$ret = $mediaService->uploadMedia($dto, $resultDTO, $messages);
        $ret = new \stdClass();
        $ret->url = \Infrastructure\CommonFunction::getServerHost().'/uploads/'.\basename($dto->file);
        return new JsonResponse([
            'isSuccess' => true,      
            'messages'  => [],
            'data' => $ret,
        ]);
        
    }
}
