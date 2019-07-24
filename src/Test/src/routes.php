<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    //Source
    $app->post('/coordinator/question/source/create', Test\Handlers\CreateSourceHandler::class, 'question.source.create');
    $app->put('/coordinator/question/source/update', Test\Handlers\UpdateSourceHandler::class, 'question.source.update');
    $app->delete('/coordinator/question/source/delete', Test\Handlers\DeleteSourceHandler::class, 'question.source.delete');
    $app->get('/coordinator/question/sources', Test\Handlers\GetSourceHandler::class, 'question.sources');

    
    //Type
    $app->post('/coordinator/question/type/create', Test\Handlers\CreateTypeHandler::class, 'question.type.create');
    $app->get('/coordinator/question/types', Test\Handlers\GetTypesHandler::class, 'question.types');
    $app->post('/coordinator/question/subType/create', Test\Handlers\CreateSubTypeHandler::class, 'question.subType.create');
    
   
    //Test
    $app->post('/coordinator/test/create', Test\Handlers\CreateTestHandler::class, 'test.create');
    $app->post('/coordinator/test/update', Test\Handlers\UpdateTestHandler::class, 'test.update');
    $app->get('/coordinator/test/tests', Test\Handlers\GetTestHandler::class, 'test.tests');
    $app->post('/coordinator/test/viewSampleExam', Test\Handlers\ViewSampleExamHandler::class, 'test.viewSampleExam');
    $app->delete('/coordinator/test/delete', Test\Handlers\DeleteTestHandler::class, 'test.delete');

    $app->get('/coordinator/test/templates', Test\Handlers\GetTestTemplateHandler::class, 'test.templates');

    //Exam
    $app->get('/coordinator/exam/candidates', Test\Handlers\GetCandidateHandler::class, 'exam.candidates');
    $app->post('/coordinator/exam/create', Test\Handlers\CreateExamHandler::class, 'exam.create');
    $app->post('/coordinator/exam/update', Test\Handlers\UpdateExamHandler::class, 'exam.update');
    $app->post('/coordinator/exam/updateTest', Test\Handlers\UpdateTestOfExamHandler::class, 'exam.updateTest');
    $app->post('/coordinator/exam/enterPin', Test\Handlers\EnterPinHandler::class, 'exam.enterPin');
    $app->post('/coordinator/exam/doExam', Test\Handlers\DoExamHandler::class, 'exam.doExam');
    $app->post('/coordinator/pin/refresh', Test\Handlers\RefreshPinHandler::class, 'pin.refresh');
    $app->post('/coordinator/exam/updateAnswer', Test\Handlers\UpdateAnswerHandler::class, 'exam.updateAnswer');
    $app->post('/coordinator/exam/synchronyTime', Test\Handlers\SynchronyTimeHandler::class, 'exam.synchronyTime');
    $app->post('/coordinator/exam/finish', Test\Handlers\FinishExamHandler::class, 'exam.finish');
    $app->post('/coordinator/exam/updateQuestionMark', Test\Handlers\UpdateQuestionMarkHandler::class, 'exam.updateQuestionMark');
    $app->get('/coordinator/exam/viewExamResult', Test\Handlers\ViewExamResultHandler::class, 'exam.viewExamResult');
    $app->post('/coordinator/exam/exams', Test\Handlers\ViewListExamHandler::class, 'exam.exams');
    $app->delete('/coordinator/exam/delete', Test\Handlers\DeleteExamHandler::class, 'exam.delete');
    
    $app->post('/coordinator/exam/exportCandidateResult', Test\Handlers\ExportCandidateResultHandler::class, 'exam.exportCandidateResult');
    $app->get('/coordinator/exam/exportCandidateResult', Test\Handlers\ExportCandidateResultHandler::class, 'exam.exportCandidateResult');
  
    // Question
    $app->post('/coordinator/questions/create', Test\Handlers\CreateQuestionHandler::class, 'questions.create');
    $app->post('/coordinator/questions/update', Test\Handlers\UpdateQuestionHandler::class, 'questions.update');
    $app->delete('/coordinator/questions/delete', Test\Handlers\DeleteQuestionHandler::class, 'questions.delete');
    $app->post('/coordinator/questions/import', Test\Handlers\ImportQuestionHandler::class, 'questions.import');
    $app->post('/coordinator/question/questions', Test\Handlers\GetQuestionHandler::class, 'question.questions');
    $app->post('/coordinator/question/export', Test\Handlers\ExportQuestionHandler::class, 'question.export');

    $app->get('/coordinator/question/type', Test\Handlers\GetTypeHandler::class, 'question.types');
};
