<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Repositories;

use Doctrine\ODM\MongoDB\DocumentRepository;
use function array_reduce;
use function trim;

class AbstractRepository extends DocumentRepository
{    
    protected $dm;
    /**
     * Return a string of scopes, separated by space
     * from ScopeEntityInterface[]
     *
     * @param ScopeEntityInterface[] $scopes
     * @return string
     */
    protected function scopesToString(array $scopes) : string
    {
        if (empty($scopes)) {
            return '';
        }

        return trim(array_reduce($scopes, function ($result, $item) {
            return $result . ' ' . $item->getIdentifier();
        }));
    }

    


    /**
     * Get the value of dm
     */ 
    public function getDm()
    {
        return $this->dm;
    }

    /**
     * Set the value of dm
     *
     * @return  self
     */ 
    public function setDm($dm)
    {
        $this->dm = $dm;

        return $this;
    }
}
