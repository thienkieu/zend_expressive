<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication for the canonical source repository
 * @copyright Copyright (c) 2017-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Test\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Hydrator\ReflectionHydrator;
use Test\DTOs\SectionDTO;

class RequestToSectionDTOMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $hydrator = new ReflectionHydrator();
        
        $sectionDTO = $hydrator->hydrate(
            $request->getParsedBody(),
            (new \ReflectionClass(SectionDTO::class))->newInstanceWithoutConstructor()
        );

        return $handler->handle($request->withAttribute('dtoObject', $sectionDTO));
    }
}