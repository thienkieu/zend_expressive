<?php

declare(strict_types=1);

namespace App\Handler;

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

class VerifyLogConfigHandler implements RequestHandlerInterface
{
    /** @var string */
    private $containerName;

    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var Psr\Container\ContainerInterface */
    private $container;

    public function __construct(
        string $containerName,
        ContainerInterface $container,
        Router\RouterInterface $router,
        ?TemplateRendererInterface $template = null
    ) {
        $this->containerName = $containerName;
        $this->container = $container;
        $this->router        = $router;
        $this->template      = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $logger = $this->container->get('logger');
        $logger->info('Informational message');
        $logger->debug('debug info');

        return new JsonResponse([
            'welcome' => 'Verify log config',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
        ]);
        
    }
}
