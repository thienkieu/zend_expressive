<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication for the canonical source repository
 * @copyright Copyright (c) 2017-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Test\Middlewares\Validators;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;
use Zend\Diactoros\Response\JsonResponse;

class CreateSectionValidatorMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new StringLength(['min' => 6, 'max' => 12]));
        
        if ($validatorChain->isValid($request->getAttribute('name'))) {
            return $handler->handle($request);
        }

        $error = [
            'name' => []
        ];
        foreach ($validatorChain->getMessages() as $message) {
            $error['name'][] = $message;
        }
        return new JsonResponse($error);
    }
}
