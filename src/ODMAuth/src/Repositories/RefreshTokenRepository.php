<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Repositories;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\RefreshTokenEntity;

class RefreshTokenRepository extends AbstractRepository implements RefreshTokenRepositoryInterface
{
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity;
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $refreshTokenDocument = new \ODMAuth\Documents\RefreshTokenDocument();
        $refreshTokenDocument->setId($refreshTokenEntity->getIdentifier());
        $refreshTokenDocument->setAccessTokenId($refreshTokenEntity->getAccessToken()->getIdentifier());
        $refreshTokenDocument->setRevoked(0);
        $refreshTokenDocument->setExpiresAt(
            $refreshTokenEntity->getExpiryDateTime()->getTimestamp()
        );

        try{
            $this->dm->persist($refreshTokenDocument);
            $this->dm->flush();
        } catch(\Exception $e) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }        
    }

    public function revokeRefreshToken($tokenId)
    {
        $document = $this->find($tokenId);
        $document->setRevoked(1);

        $this->dm->flush();
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        $document = $this->find($tokenId);
        if (empty($document)){
            return false;
        }


        return $document->getRevoked() ? (bool) $document->getRevoked() : false;
    }
}
