<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Authentication\OAuth2;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', [App\Handler\HomePageHandler::class], 'home');
   // App\Middleware\ValidatorMiddleware::class,
    //$app->get('/', Zend\Expressive\Authentication\AuthenticationMiddleware::class, App\Handler\HomePageHandler::class, 'home');
    $app->get('/view/{name}', [App\Validator\ValidatorMiddleware::class, App\Handler\ViewPageHandler::class], 'view');
    //$app->get('/', App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
    $app->post('/token', OAuth2\TokenEndpointHandler::class, 'api.token');
    $app->get('/publickey', App\Handler\GetPublicKeyHandler::class, 'api.publickey');

    //Source
    $app->post('/coordinator/question/source/create', Test\Handlers\CreateSourceHandler::class, 'question.source.create');
    $app->get('/coordinator/question/sources', Test\Handlers\GetSourceHandler::class, 'question.sources');

    
    //Test
    $app->post('/coordinator/test/create', Test\Handlers\CreateTestHandler::class, 'test.create');
    $app->get('/coordinator/test/tests', Test\Handlers\GetTestHandler::class, 'test.tests');

    //Exam
    $app->get('/coordinator/exam/candidates', Test\Handlers\GetCandidateHandler::class, 'exam.candidates');
    $app->post('/coordinator/exam/create', Test\Handlers\CreateExamHandler::class, 'exam.create');
    $app->post('/coordinator/exam/enterPin', Test\Handlers\EnterPinHandler::class, 'exam.enterPin');
    $app->post('/coordinator/exam/doExam', Test\Handlers\DoExamHandler::class, 'exam.doExam');
    $app->post('/coordinator/pin/refresh', Test\Handlers\RefreshPinHandler::class, 'pin.refresh');
    $app->post('/coordinator/exam/updateAnswer', Test\Handlers\UpdateAnswerHandler::class, 'exam.updateAnswer');
    $app->post('/coordinator/exam/updateRepeatTimes', Test\Handlers\UpdateRepeatTimesHandler::class, 'exam.updateRepeatTimes');
   // $app->get('/test/create-section', Test\Handlers\CreateTestHandler::class, 'section.create');

    $app->get('/test/search-section', Test\Handlers\SearchSectionHandler::class, 'search');

    // Verify config
    $app->get('/verify-odm', Test\Handlers\VerifyODMConfigHandler::class, 'verifyODM');
    $app->get('/verify-log', App\Handler\VerifyLogConfigHandler::class, 'verifyLOG');
    $app->post('/verify-rsa', App\Handler\VerifyRSAConfigHandler::class, 'verifyRSA');
    $app->post('/verify-cors', App\Handler\VerifyCORSConfigHandler::class, 'verifyCORS');

    $app->get('/viewLog', App\Handler\GetLogHandler::class, 'log');


    /*$app->post('/test/create-section/{name}', [        
        Test\Handlers\CreateSectionHandler::class
    ], 'section.create');
    */
    
    // Question
    $app->post('/coordinator/questions/create', Test\Handlers\CreateSectionHandler::class, 'questions.create');
    $app->post('/coordinator/questions/import', Test\Handlers\ImportQuestionHandler::class, 'questions.import');


    //odm auth
    $app->get('/odmauth/setup', ODMAuth\Handler\SetupSampleDataHandler::class, 'odmauth.setup');

    

    
};
