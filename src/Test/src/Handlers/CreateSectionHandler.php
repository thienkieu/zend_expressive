<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Test\Services\SectionServiceInterface;

class CreateSectionHandler implements RequestHandlerInterface
{
    /** @var string */
    private $containerName;

    /** @var Router\RouterInterface */
    private $router;

    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(
        string $containerName,
        ContainerInterface $container,
        Router\RouterInterface $router
    ) {
        $this->containerName = $containerName;
        $this->container = $container;
        $this->router        = $router;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $dto = $request->getAttribute('dtoObject');
        $sectionService = $this->container->get(SectionServiceInterface::class);
        $ret = $sectionService->createSection($dto, $resultDTO, $messages);

        return new JsonResponse([
            'isSuccess' => $ret,      
            'messages'  => $messages,
            'data' => $resultDTO
        ]);
        
    }
}
