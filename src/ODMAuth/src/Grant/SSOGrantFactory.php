<?php

declare(strict_types=1);

namespace ODMAuth\Grant;

use Psr\Container\ContainerInterface;

class SSOGrantFactory
{
    public function __invoke(ContainerInterface $container) : SSOGrant
    {
        $dm = $container->get(\Config\AppConstant::DocumentManager);
        return new SSOGrant($dm->getRepository(\ODMAuth\Documents\UserDocument::class), $dm->getRepository(\ODMAuth\Documents\RefreshTokenDocument::class));
    }
}
