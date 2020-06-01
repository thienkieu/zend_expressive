<?php

declare(strict_types=1);

namespace ODMAuth\Services;

use Zend\Log\Logger;
use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Doctrine\Common\Collections\ArrayCollection;


class UserService implements Interfaces\UserServiceInterface
{
    protected $container;
    protected $dm;
    protected $options;
    protected $translator= null;
    protected $user = null;

    public function __construct($container, $options = []) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get(\Config\AppConstant::DocumentManager);
        $this->translator = $this->container->get(\Config\AppConstant::Translator);
    }

    public function isHandler($param, $options = []){
        return true;
    }
    
    public function getUserById($userId) {
        $userRepository = $this->dm->getRepository(\ODMAuth\Documents\UserDocument::class);
        $userDocument = $userRepository->find($userId); 
        return $userDocument;
    }
}
