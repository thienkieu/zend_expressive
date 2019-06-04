<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Repositories;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\ClientEntity;

use function password_verify;

class ClientRepository extends AbstractRepository implements ClientRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getClientEntity(
        $clientIdentifier,
        $grantType = null,
        $clientSecret = null,
        $mustValidateSecret = true
    ) {
        $client = $this->findOneBy(['name'=>$clientIdentifier]);
        
        if (empty($client)) {
            return ;
        }

        if (empty($client) || ! $this->isGranted($client, $grantType)) {
            return;
        }

        if ($mustValidateSecret
            && (empty($client->getSecret())) || ! password_verify((string) $clientSecret, $client->getSecret())
        ) {
            return;
        }

        return new ClientEntity($clientIdentifier, $client->getName(), $client->getRedirect());
    }

    /**
     * Check the grantType for the client value, stored in $row
     *
     * @param array $row
     * @param string $grantType
     * @return bool
     */
    protected function isGranted($row, string $grantType = null) : bool
    {
        switch ($grantType) {
            case 'authorization_code':
                return ! ($row->getPersonalAccessClient() || $row->getPasswordClient());
            case 'personal_access':
                return (bool) $row->getPersonalAccessClient();
            case 'password':
                return (bool) $row->getPasswordClient();
            default:
                return true;
        }
    }
}
