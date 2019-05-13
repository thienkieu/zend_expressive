<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Repositories;

use Psr\Container\ContainerInterface;

class AccessTokenRepositoryFactory
{
    public function __invoke(ContainerInterface $container) : AccessTokenRepository
    {
        $dm = $container->get(\Config\AppConstant::DocumentManager);
        $repo = $dm->getRepository(\ODMAuth\Documents\AccessTokenDocument::class);
        $repo->setDm($dm);
        
        return $repo;
    }
}
