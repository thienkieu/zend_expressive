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
use App\Documents;


class VerifyODMConfigHandler implements RequestHandlerInterface
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
        $dm =  $this->container->get('documentManager');    
        
        $writing = new \Test\Documents\WritingSectionDocument();
        $writing->setContent('this is content writing');

        $reading = new \Test\Documents\ReadingSectionDocument();
        $reading->setContent('this is reading content');
        $reading->addWriting($writing);

        $dm->persist($reading);
        $dm->flush();

        //$repository = $dm->getRepository(Documents\SectionDocument::class);
        //$obj = $repository->find("5caac4c7ce10c916c8007032");
               
        //$builder = $dm->createQueryBuilder(array(Documents\ReadingSectionDocument::class, Documents\ListeningSectionDocument::class));
        //$builder = $builder->field('questions.content')->equals(new \MongoRegex('/.*dsf*/i'));
        //$query = $builder->getQuery();
        //$documents = $query->execute();
//
        //$hydrator = new ReflectionHydrator();
        //$data = $hydrator->extract($documents);

        // $questionContent  = [];
        // foreach($documents as $document){
        //     $questions = $document->getQuestions();
        //     foreach($questions as $question){
        //         $questionContent = $question->getContent();
        //     }
        // }

        return new JsonResponse([
            'welcome' => 'Congratulations! You have installed the zend-expressive skeleton application.',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
            //'content' => $questionContent,           
            //'data' => $data,
        ]);
        
    }
}
