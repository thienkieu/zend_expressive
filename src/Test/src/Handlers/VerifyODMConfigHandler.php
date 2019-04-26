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
use Test\Documents;
use Zend\Hydrator\ReflectionHydrator;

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
        $repository = $dm->getRepository(Documents\SectionDocument::class);
        $obj = $repository->find("5cc12636ce10c91f10004f3b");
               
        $builder = $dm->createQueryBuilder(Documents\SectionDocument::class);
        $builder = $builder->field('questions.content')->equals(new \MongoRegex('/.*d.*/i'));
        $query = $builder->getQuery();
        $documents = $query->execute();
        
        $hydrator = new ReflectionHydrator();
        $data = $hydrator->extract($obj);
       
        foreach($documents as $document) {

            $d = $hydrator->extract($document);

            $questions = $document->getQuestions();
            $qJson = [];
            foreach($questions as $q) {
                 $qJson[]= $hydrator->extract($q);
            }
            $d['jsonQuestions'] = $qJson;

            $documentsJson = $d;

        }
       /* $questionContent  = [];
        foreach($documents as $document){
            $questions = $document->getQuestions();
            foreach($questions as $question){
                $questionContent = $question->getContent();
            }
        }*/

        return new JsonResponse([
            'welcome' => 'Congratulations! You have installed the zend-expressive skeleton application.',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
           // 'content' => $questionContent,
            'obj' => $obj->getId(),
            'module' => 'Test',
            'data' => $data,
            'documents' => $documentsJson,
        ]);
        
    }
}
