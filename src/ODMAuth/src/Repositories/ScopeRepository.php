<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\ScopeEntity;

class ScopeRepository extends AbstractRepository implements ScopeRepositoryInterface
{
    public function getScopeEntityByIdentifier($identifier)
    {
        $scope = $this->find($identifier);
        
        if (empty($scope)) {
            return;
        }

        $scopeEntity = new ScopeEntity();
        $scopeEntity->setIdentifier($scope->getId());
        return $scopeEntity;
    }

    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        return $scopes;
    }
}
