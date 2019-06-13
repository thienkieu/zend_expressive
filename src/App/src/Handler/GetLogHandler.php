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

class GetLogHandler implements RequestHandlerInterface
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
        $dir = realpath('Logs');
        $file = $dir.'/'.date('Y-m-d').'.txt';
        
        if (file_exists($file)) {
            $logs = file_get_contents($file);
        }

        return new HtmlResponse($logs);
    }
}
