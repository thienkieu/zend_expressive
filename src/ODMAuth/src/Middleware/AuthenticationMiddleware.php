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
use Config\AppConstant;
use Zend\Diactoros\Response\JsonResponse;

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
        

        //Don't verify for hardcode token form other system such as CRM
        $token = $request->getHeader('Authorization');
        $authenticationExcludeToken = $config[\Config\AppConstant::authenticationExcludeToken];
        if (count($token) > 0 && in_array($token[0], $authenticationExcludeToken)) {
            return $handler->handle($request);
        }
        
        //Require login for specify url
        $rotuer = $request->getAttribute(RouteResult::class);
        $routerName = $rotuer->getMatchedRouteName(); 
        if ($routerName && !in_array($routerName, $authenticationExcludeUrl)) { 
            $user = $this->auth->authenticate($request);
            if ($user) {
                $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class, ['user' => $user]);
                $authorizationService->setUser($user);
                $ok = $authorizationService->isAllow($user->getIdentity(), $routerName, $messages);
                if ($ok) {
                    return $handler->handle($request->withAttribute(UserInterface::class, $user));
                } else {
                    $translator = $this->container->get(AppConstant::Translator);
                    return new JsonResponse([
                        'isSuccess' => false, 
                        'messages'  => $translator->translate('You donot have permissions'),
                        'data' => new \stdClass(),
                    ], 403);
                }             
            }

            return $this->auth->unauthorizedResponse($request);
        }
        
        $authenticationRequirePin = $config[\Config\AppConstant::authenticationRequirePin];
        if ($routerName && in_array($routerName, $authenticationRequirePin)) {
            $user = $this->auth->authenticate($request);
            if ($user) {
                $doExamAuthorizationService = $this->container->get(\ODMAuth\Services\Interfaces\DoExamAuthorizationServiceInterface::class, ['user' => $user]);
                $doExamAuthorizationService->setCandidateInfo($user);
                $tokenValue = $token[0];
                $t = \explode(' ', $tokenValue);
                $doExamAuthorizationService->setToken($t[1]);
                return $handler->handle($request);
            }

            return $this->auth->unauthorizedResponse($request);
        }
        
        return $handler->handle($request);
    }
}
