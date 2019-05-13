<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Repositories;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\AuthCodeEntity;

class AuthCodeRepository extends AbstractRepository implements AuthCodeRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getNewAuthCode()
    {
        return new AuthCodeEntity;
    }

    /**
     * {@inheritDoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $authCodeDocument = new \ODMAuth\Documents\AuthCodeDocument();
        $authCodeDocument->setId($authCodeEntity->getIdentifier());
        $authCodeDocument->setUserId($authCodeEntity->getUserIdentifier());
        $authCodeDocument->setClientId($authCodeEntity->getClient()->getIdentifier());
        $authCodeDocument->setScopes($this->scopesToString($authCodeEntity->getScopes()));
        $authCodeDocument->setRevoked(0);
        $authCodeDocument->setExpiresAt(date(
            'Y-m-d H:i:s',
            $authCodeEntity->getExpiryDateTime()->getTimestamp()
        ));
      
        try{
            $this->dm->persist($authCodeDocument);
            $this->dm->flush();
        } catch(\Exception $e){
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function revokeAuthCode($codeId)
    {
        $document = $this->find($codeId);
        $document->setRevoked(1);
        $this->dm->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        $document = $this->find($codeId);
        if (empty($document)) {
            return false;
        }
        
        return !empty($document->getRevoked()) ? (bool) $document->getRevoked() : false;
    }
}
