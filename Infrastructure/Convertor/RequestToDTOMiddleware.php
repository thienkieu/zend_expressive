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

use Zend\Log\Logger;
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
        try{
            $rotuer = $request->getAttribute(RouteResult::class);
            $routerName = $rotuer->getMatchedRouteName();        
            if ($routerName) {
                $appConfig = $this->container->get(\Config\AppConstant::AppConfig);
                $routertoDTO = $appConfig['requestToDTO'];
                if (isset($routertoDTO[$routerName])) {
                    $dtoName = $routertoDTO[$routerName];

                    $request = $request->withAttribute(AppConstant::RequestDTOName, $dtoName);
                    
                    $validator = $this->container->get(ValidatorRequestInterface::class);
                    $messageResponse = new JsonResponse([]);                
                    $isValid = $validator->valid($request, $messageResponse);
                    if (!$isValid) {
                        return $messageResponse;
                    }
                    
                    $convertorToDTO = $this->container->get(RequestToDTOConvertorInterface::class);
                    $jsonData = $request->getParsedBody();
                    $dto = $convertorToDTO->convertToDTO($jsonData, $dtoName);
                    
                    return $handler->handle($request->withAttribute(\Config\AppConstant::DTODataFieldName, $dto));
                } else {
                    $body =  $request->getParsedBody();
                    return $handler->handle($request->withAttribute(\Config\AppConstant::DTODataFieldName, $body));
                }
            }
        } catch(\Exception $e){
            $logger = $this->container->get(Logger::class);
            $logger->info($e);

            $translator = $this->container->get(\Config\AppConstant::Translator);
            $messages[] = $translator->translate('There is error with format data, Please check it again');
            
            return \Infrastructure\CommonFunction::buildResponseFormat(false, $messages);
        }  
        return $handler->handle($request);      
    }

}