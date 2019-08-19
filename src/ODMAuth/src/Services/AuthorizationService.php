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

    public function getListPermission($dto, $itemPerPage, $pageNumber) {
        $repository = $this->dm->getRepository(\ODMAuth\Documents\PermissionDocument::class);
        $data = $repository->getPermissions($dto, $itemPerPage, $pageNumber);
        $permissionDocuments = $data['permissions'];
 
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $permissions = [];
        foreach($permissionDocuments as $document) {
            $dtoObject = $documentToDTOConvertor->convertToDTO($document);
            $permission[] = $dtoObject;
        }
        
        
        return [
            'totalDocument' => $data['totalDocument'],
            'permissions' => $permission 
        ];
    }


    public function assignUserPermission($dto, &$messages) {
        $userRepository = $this->dm->getRepository(\ODMAuth\Documents\UserDocument::class);
        $permissionRepository = $this->dm->getRepository(\ODMAuth\Documents\PermissionDocument::class);

        $userDocument = $userRepository->findOneBy(['objectId' =>$dto->getUserId()]);
        if (!$userDocument) {
            $userDocument = new \ODMAuth\Documents\UserDocument();
            $userDocument->setObjectId($dto->getUserId());
            $this->dm->persist($userDocument);
        }

        $permissionDocuments = $permissionRepository->getListByIds($dto->getPermissionsIds());
        if (count($permissionDocuments) < 1) {
            $messages[] = $this->translator->translate('Permission not found');
            return false;
        }

        $userDocument->setPermissionDocument($permissionDocuments);
        $this->dm->flush();

        $messages[] = $this->translator->translate('Your permission have been assigned');
        return true;
        
    } 
    
}
