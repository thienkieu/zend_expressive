<?php

declare(strict_types=1);

namespace ODMAuth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class GetListPermissionHandler implements RequestHandlerInterface
{
   
    /** @var Psr\Container\ContainerInterface */
    private $container;

    public function __construct(        
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $queryData = $request->getQueryParams();
        $pageNumber = isset($queryData['pageNumber']) ? $queryData['pageNumber'] : 1;
        $itemPerPage = isset($queryData['itemPerPage']) ? $queryData['itemPerPage'] : 25;
        
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);

        $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
        $data = $authorizationService->getListPermission($dto, $itemPerPage, $pageNumber);

        $ret = new \stdClass();
        $ret->permissions = $data['permissions'];
        $ret->totalPage = $data['totalDocument'] % $itemPerPage > 0 ? (int) ($data['totalDocument'] / $itemPerPage) + 1: $data['totalDocument'] / $itemPerPage ;
        $ret->pageNumber = $pageNumber;
        $ret->itemPerPage = $itemPerPage;

        return \Infrastructure\CommonFunction::buildResponseFormat(true, [], $ret);
        
    }
}
