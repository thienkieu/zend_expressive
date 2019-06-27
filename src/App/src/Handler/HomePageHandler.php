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
        
        // $translator = $this->container->get('translator');
        // $message = $translator->translate('Skeleton Application',['%abc%' => 'def']);
        // $currentLocale = $translator->getLocale();

        // $sectionService = $this->container->get(\Test\Services\SectionServiceInterface::class);

        /*$entityManager =  $this->container->get('entityManager');      
        $dm =  $this->container->get('documentManager');        
        
        $readingSection  = new \Test\Documents\ReadingSection();
        $readingSection->setContent('reading section');

        $answer = new \Test\Documents\Answer();
        $answer->setContent('answer content 1');
        $answer->setIsCorrect(true);

        $answer2 = new \Test\Documents\Answer();
        $answer2->setContent('answer content 2');
        $answer2->setIsCorrect(false);


        $question = new \Test\Documents\Question();
        $question->setContent('this is question content');
        $question->addAnswer($answer);
        $question->addAnswer($answer2);

        $readingSection->addQuestion($question);
        $dm->persist($readingSection);


        $listeningSection  = new \Test\Documents\ListeningSection();
        $listeningSection->setContent('listening section');

        $answer = new \Test\Documents\Answer();
        $answer->setContent('answer content 1');
        $answer->setIsCorrect(true);

        $answer2 = new \Test\Documents\Answer();
        $answer2->setContent('answer content 2');
        $answer2->setIsCorrect(false);


        $question = new \Test\Documents\Question();
        $question->setContent('this is question content of listening');
        $question->addAnswer($answer);
        $question->addAnswer($answer2);

        $listeningSection->addQuestion($question);
        $dm->persist($listeningSection);


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
            //'translator-message' => $message,
            //'locale' => $currentLocale,
            'data' => $request->getParsedBody()
        ]);
        
    }
}
