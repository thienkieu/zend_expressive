<?php

declare(strict_types=1);

namespace ODMAuth\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class AuthorizationService implements Interfaces\AuthorizationServiceInterface
{
    protected $container;
    protected $dm;
    protected $options;
    protected $translator= null;

    public function __construct($container, $options = []) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get(\Config\AppConstant::DocumentManager);
        $this->translator = $this->container->get(\Config\AppConstant::Translator);
    }

    public function isHandler($param, $options = []){
        return true;
    }
    
    public function isAllow($userId, $action, &$messages) {
        $userRepository = $this->dm->getRepository(\ODMAuth\Documents\UserDocument::class);
        $userDocument = $userRepository->findOneBy(['objectId'=>$userId]);
        if (!$userDocument) {
            $messages[] = $this->translator->translate('You do not have permission');
            return false;
        }

        $userPermissions = $userDocument->getPermissionDocument();
        foreach($userPermissions as $permission) {
            $codeFunctions = $permission->getCodeFunctions();
            if (in_array($action, $codeFunctions)) {
                return true;
            }
        }

        $groupRepository = $this->dm->getRepository(\ODMAuth\Documents\GroupsDocument::class);
        $groupsDocument = $groupRepository->findBy(["userDocument"=>$userId]);
        foreach($groupsDocument as $groupDocument) {
            $groupPermissions = $groupDocument->getPermissionDocument();
            foreach($groupPermissions as $permission) {
                $codeFunctions = $permission->getCodeFunctions();
                if (in_array($action, $codeFunctions)) {
                    return true;
                }
            }
        }

        $messages[] = $this->translator->translate('You do not have permission');
        return false;
    }
    
}
