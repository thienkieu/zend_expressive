<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication for the canonical source repository
 * @copyright Copyright (c) 2017-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Validator\ValidatorRequestInterface;
use Zend\Expressive\Router\RouteResult;
use Config\AppConstant;

class RequestToDTOMiddleware implements MiddlewareInterface
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $rotuer = $request->getAttribute(RouteResult::class);
        $routerName = $rotuer->getMatchedRouteName();
        if ($routerName) {
            $appConfig = $this->container->get('config');
            $routertoDTO = $appConfig['requestToDTO'];
            $dtoName = $routertoDTO[$routerName];

            $request = $request->withAttribute(AppConstant::RequestDTOName, $dtoName);
            
            $validator = $this->container->get(ValidatorRequestInterface::class);
            $messageResponse = new JsonResponse([]);                
            $isValid = $validator->valid($request, $messageResponse);
            if (!$isValid) {
                return $messageResponse;
            }

            $convertorToDTO = $this->container->get(RequestToDTOConvertorInterface::class);
            $dto = $convertorToDTO->convertToDTO($request);
            
            return $handler->handle($request->withAttribute('dtoObject', $dto));
        }
        
        return $handler->handle($request);      
    }

}