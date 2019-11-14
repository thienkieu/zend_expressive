<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication for the canonical source repository
 * @copyright Copyright (c) 2017-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Authentication\AuthenticationInterface;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Router\RouteResult;

class AuthenticationMiddleware extends \Zend\Expressive\Authentication\AuthenticationMiddleware
{
    /**
     * @var AuthenticationInterface
     */
    protected $auth;

    protected $container;

    public function __construct(AuthenticationInterface $auth, $container)
    {
        $this->auth = $auth;
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {   
        $config = $this->container->get(\Config\AppConstant::AppConfig);
        $authenticationExcludeUrl = $config[\Config\AppConstant::AuthenticationExcludeUrl];
        
        $token = $request->getHeader('Authorization');
        $authenticationExcludeToken = $config[\Config\AppConstant::authenticationExcludeToken];
        if (count($token) > 0 && in_array($token[0], $authenticationExcludeToken)) {
            return $handler->handle($request);
        }
        
        $rotuer = $request->getAttribute(RouteResult::class);
        $routerName = $rotuer->getMatchedRouteName(); 
        if ($routerName && !in_array($routerName, $authenticationExcludeUrl)) { 
            $user = $this->auth->authenticate($request);
            if (null !== $user) {
                $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class, ['user' => $user]);
                $authorizationService->setUser($user);
                $ok = $authorizationService->isAllow($user->getIdentity(), $routerName, $messages);
                if ($ok) {
                    return $handler->handle($request->withAttribute(UserInterface::class, $user));
                }                
            }

            return $this->auth->unauthorizedResponse($request);
        }

        $user = $this->auth->authenticate($request);
        if ($user) {
            $doExamAuthorizationService = $this->container->get(\ODMAuth\Services\Interfaces\DoExamAuthorizationServiceInterface::class, ['user' => $user]);
            $doExamAuthorizationService->setCandidateInfo($user);
        }

        return $handler->handle($request);
    }
}
