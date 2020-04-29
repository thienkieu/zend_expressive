<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Repositories;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\AccessTokenEntity;

use function array_key_exists;
use function implode;
use function sprintf;

class AccessTokenRepository extends AbstractRepository implements AccessTokenRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        $accessToken->setUserIdentifier($userIdentifier);
        return $accessToken;
    }

    /**
     * {@inheritDoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $accessTokenDocument = new \ODMAuth\Documents\AccessTokenDocument();
        $accessTokenDocument->setId( $accessTokenEntity->getIdentifier());
        $accessTokenDocument->setUserId( $accessTokenEntity->getUserIdentifier());
        $accessTokenDocument->setClientId( $accessTokenEntity->getClient()->getIdentifier());
        $accessTokenDocument->setScopes( $this->scopesToString($accessTokenEntity->getScopes()));
        $accessTokenDocument->setRevoked(0);
        $accessTokenDocument->setExpiresAt(
            $accessTokenEntity->getExpiryDateTime()->getTimestamp()
        );
              
        try {
            $this->dm->persist($accessTokenDocument);
            $this->dm->flush(); 
            
        }
        catch(\Exception $e) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
        
    }

    /**
     * {@inheritDoc}
     */
    public function revokeAccessToken($tokenId)
    {
        $accessTokenDocument = $this->find($tokenId);
        $accessTokenDocument->setRevoked(1);
        $this->dm->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $accessTokenDocument = $this->find($tokenId);
       
        if (empty($accessTokenDocument)) {
            return false;
        }
      
        return $accessTokenDocument->getRevoked() ? (bool) $accessTokenDocument->getRevoked() : false;
    }
}
