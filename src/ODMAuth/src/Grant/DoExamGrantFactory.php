<?php

declare(strict_types=1);

namespace ODMAuth\Grant;

use Psr\Container\ContainerInterface;

class DoExamGrantFactory
{
    public function __invoke(ContainerInterface $container) : DoExamGrant
    {
        $dm = $container->get(\Config\AppConstant::DocumentManager);
        return new DoExamGrant($dm->getRepository(\Test\Documents\Exam\ExamDocument::class),$container->get(\Test\Services\DoExamServiceInterface::class), $dm->getRepository(\ODMAuth\Documents\RefreshTokenDocument::class));
    }
}
