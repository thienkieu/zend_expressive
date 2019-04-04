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

class HomePageHandler implements RequestHandlerInterface
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
        $entityManager =  $this->container->get('entityManager');      
        $dm =  $this->container->get('documentManager');        
        
        $userDocument  = new \App\Documents\UserDocument();
        $userDocument->setTitle('title');
        $userDocument->setBody('sdfsdfsdfsdfsdf');
        $dm->persist($userDocument);
        $dm->flush();


        /*$userEntity = new \App\Entity\UserEntity();
        $userEntity->setId('dsfsfd');
        $userEntity->setName('dsfsfd');
        $userEntity->setSkype('dsfsfd');
        $userEntity->setEmail('thien1988@gmail.com');        
        
        $entityManager->persist($userEntity);
        $entityManager->flush();
       */
        return new JsonResponse([
            'welcome' => 'Congratulations! You have installed the zend-expressive skeleton application.',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
        ]);
        
    }
}
