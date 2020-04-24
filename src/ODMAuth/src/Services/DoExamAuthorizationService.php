<?php

declare(strict_types=1);

namespace ODMAuth\Services;

use Zend\Log\Logger;
use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Doctrine\Common\Collections\ArrayCollection;


class DoExamAuthorizationService implements Interfaces\DoExamAuthorizationServiceInterface
{
    protected $container;
    protected $dm;
    protected $options;
    protected $translator= null;
    protected $candidateInfo = null;
    protected $token = null;

    public function __construct($container, $options = []) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get(\Config\AppConstant::DocumentManager);
        $this->translator = $this->container->get(\Config\AppConstant::Translator);
    }

    public function isHandler($param, $options = []){
        return true;
    }

    public function setCandidateInfo($canidateInfo) {
        $candidateInfo = $canidateInfo->getIdentity();
        $extractInfo = \explode("###@@###", $candidateInfo);
        if (strpos($candidateInfo, 'pin') !== false ) {
            $info = new \stdClass();
            $info->examId = $extractInfo[1];
            $info->candidateId = $extractInfo[2];

            $this->candidateInfo = $info;            
        }
    }

    public function getCandidateInfo() {
        return $this->candidateInfo;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function getToken() {
        return $this->token;
    }
}
