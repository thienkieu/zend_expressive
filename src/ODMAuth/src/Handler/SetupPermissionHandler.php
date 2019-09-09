<?php

declare(strict_types=1);

namespace ODMAuth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class SetupPermissionHandler implements RequestHandlerInterface
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
        $dm = $this->container->get(\Config\AppConstant::DocumentManager);

        $systemCodeFunctions = [];
        $systemCodeFunctions[] ="odmauth.setup";
        $systemCodeFunctions[] ="odmauth.setupPermission";
        $systemCodeFunctions[] ="odmauth.verifyPermission";
        $systemCodeFunctions[] ="odmauth.permissions";
        $systemCodeFunctions[] ="odmauth.assignUserPermission";

        $systemPermissionDocument = new \ODMAuth\Documents\PermissionDocument();
        $systemPermissionDocument->setBusinessName("System management");
        $systemPermissionDocument->setCodeFunctions($systemCodeFunctions);
        $dm->persist($systemPermissionDocument);


        $questionCodeFunctions = [];
        $questionCodeFunctions[] ="questions.create";
        $questionCodeFunctions[] ="questions.update";
        $questionCodeFunctions[] ="questions.delete";
        $questionCodeFunctions[] ="questions.import";
        $questionCodeFunctions[] ="question.questions";
        $questionCodeFunctions[] ="question.export";
        $questionCodeFunctions[] ="question.source.create";
        $questionCodeFunctions[] ="question.source.update";
        $questionCodeFunctions[] ="question.source.delete";
        $questionCodeFunctions[] ="question.sources";
        $questionCodeFunctions[] ="question.uploadMedia";
        $questionCodeFunctions[] ="question.type.create";
        $questionCodeFunctions[] ="question.types";
        $questionCodeFunctions[] ="question.subType.create";

        $questionPermissionDocument = new \ODMAuth\Documents\PermissionDocument();
        $questionPermissionDocument->setBusinessName("Question management");
        $questionPermissionDocument->setCodeFunctions($questionCodeFunctions);
        $dm->persist($questionPermissionDocument);

        $testCodeFunctions = [];
        $testCodeFunctions[] ="test.create";
        $testCodeFunctions[] ="test.createTemplate";
        $testCodeFunctions[] ="test.update";
        $testCodeFunctions[] ="test.tests";
        $testCodeFunctions[] ="test.viewSampleExam";
        $testCodeFunctions[] ="test.delete";
        $testCodeFunctions[] ="test.templates";

        $testPermissionDocument = new \ODMAuth\Documents\PermissionDocument();
        $testPermissionDocument->setBusinessName("Test management");
        $testPermissionDocument->setCodeFunctions($testCodeFunctions);
        $dm->persist($testPermissionDocument);

        $examCodeFunctions = [];
        $examCodeFunctions[] ="exam.candidates";
        $examCodeFunctions[] ="exam.create";
        $examCodeFunctions[] ="exam.update";
        $examCodeFunctions[] ="exam.updateTest";
        $examCodeFunctions[] ="exam.enterPin";
        $examCodeFunctions[] ="pin.refresh";
        $examCodeFunctions[] ="exam.updateAnswer";
        $examCodeFunctions[] ="exam.updateQuestionMark";
        $examCodeFunctions[] ="exam.updateSectionMark";

        $examCodeFunctions[] ="exam.viewExamResult";
        $examCodeFunctions[] ="exam.exams";
        $examCodeFunctions[] ="exam.delete";
        $examCodeFunctions[] ="exam.addResult";
        $examCodeFunctions[] ="exam.examJoined";
        $examCodeFunctions[] ="exam.exportPin";
        $examCodeFunctions[] ="exam.types";
        $examCodeFunctions[] ="exam.exportCandidateResult";
        $examCodeFunctions[] ="exam.exportCandidateResult";
        $examCodeFunctions[] ="exam.types";
        $examCodeFunctions[] ="exam.types";

        $examPermissionDocument = new \ODMAuth\Documents\PermissionDocument();
        $examPermissionDocument->setBusinessName("Exam management");
        $examPermissionDocument->setCodeFunctions($examCodeFunctions);
        $dm->persist($examPermissionDocument);



        $permissionArray = new ArrayCollection();
        $permissionArray->add($questionPermissionDocument);
        $permissionArray->add($testPermissionDocument);
        $permissionArray->add($examCodeFunctions);


        /*$user = new \ODMAuth\Documents\UserDocument();
        $user->setUsername('full_permission_usser');
        $user->setPassword('$2y$10$DW12wQQvr4w7mQ.uSmz37OQkKcIZrRZnpXWoYue7b5v8E/pxvsAru');
        $user->setPermissionDocument($permissionArray);
        $dm->persist($user);

        $userArray = new ArrayCollection();
        $userArray->add($user);

        $groups = new \ODMAuth\Documents\GroupsDocument();
        $groups->setName('Admin group');
        $groups->setDescription('Full permission of online test');
        $groups->setPermissionDocument($permissionArray);
        $groups->setUserDocument($userArray);

        $dm->persist($groups);*/
        $dm->flush();


        return new JsonResponse([
            'welcome' => 'setup permission data success',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
        ]);
        
    }
}
