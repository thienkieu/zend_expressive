<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;
use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

use Infrastructure\Interfaces\HandlerInterface;

class CandidateService implements CandidateServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');
    }

    public function isHandler($param, $options = []){
        return true;
    }
    
    public function getCandidates(& $candiates, & $messages, $nameOrEmail, $type, $pageNumber = 1, $itemPerPage = 25) {
        $appConfig = $this->container->get(\Config\AppConstant::AppConfig);
        $CRMConfig = $appConfig[\Config\AppConstant::CRM];
        $crmConfig = $CRMConfig[\Config\AppConstant::Candidate];
        
        $request = new Request();
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ));

        $request->setUri($crmConfig);
        $request->setMethod('POST');
        $request->setPost(new Parameters(
            [
                'pageNumber' => $pageNumber, 
                'itemPerPage'=> $itemPerPage,
                'nameOrEmail' => $nameOrEmail,
                'type' => $type,
            ]
        ));

        $client = new Client();
        $response = $client->dispatch($request);
        $data = json_decode($response->getBody());

        $candidates = $this->buildCandidateList($data->data);

        $candiates = new \stdClass();
        $candiates->itemPerPage = $data->itemPerPage;
        $candiates->pageNumber = $data->pageNumber;
        $candiates->totalPage = $data->totalPage;
        $candiates->candidates = $candidates;
        
        return true;
    }
    
    protected function buildCandidateList($data){
        $candidates = [];
        
        foreach ($data as $candidate) {
            $candidateDTO = new \Test\DTOs\Exam\CandidateDTO();
            $candidateDTO->setId('');
            $candidateDTO->setObjectId($candidate->id);
            $candidateDTO->setEmail($candidate->email);
            $candidateDTO->setType($candidate->type);
            $candidateDTO->setName($candidate->name);
            $candidateDTO->setPin('');

            $candidates[] = $candidateDTO;
        }

        return $candidates;
    }
    
}
