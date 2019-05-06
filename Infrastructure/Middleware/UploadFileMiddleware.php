<?php
namespace Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Config\AppConstant;
use Infrastructure\CommonFunction;

class UploadFileMiddleware implements MiddlewareInterface
{
    private $container;
    private $options = null;
    /**
     * Class constructor.
     */
    public function __construct($container, $options = null)
    {
        $this->container = $container;
        $this->options = $options;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $files = $request->getUploadedFiles();
        if (count($files) > 0) { 
            $config = $this->container->get('config');
            $uploadConfig = $config[AppConstant::UploadConfigName];
            $maxUploadFileSize = isset($options[AppConstant::UploadSizeAllow]) ? $options[AppConstant::UploadSizeAllow] : $uploadConfig[AppConstant::UploadSizeAllow];
            $uploadFileTypes = isset($options[AppConstant::UploadConfigFileTypes]) ? $options[AppConstant::UploadConfigFileTypes] : $uploadConfig[AppConstant::UploadConfigFileTypes];
            $uploadToFolder = isset($options[AppConstant::UploadFolder]) ? $options[AppConstant::UploadFolder] : $uploadConfig[AppConstant::UploadFolder];
            

            $isError = false;
            $messages = [];

            $translator = $this->container->get(AppConstant::Translator);
            
            $body = $request->getParsedBody(); 
            foreach($files as $key => $file) {
                if ($file->getSize() / 1024 > $maxUploadFileSize) {
                    $isError = true;
                    
                    // TODO need implement translator with params.
                    $messages[] =  $translator->translate('Size of upload file can not larger than');
                }
                $fileType = $file->getClientMediaType();
                if (!in_array($fileType,  $uploadFileTypes)) {
                    $isError = true;
                    
                    // TODO need implement translator with params.
                    $messages[] =  $translator->translate('File type is not support');
                }            
            }

            if ($isError) {
                return CommonFunction::buildResponseFormat(!$isError, $messages);
            }

            foreach($files as $file) {
                $file->moveTo($uploadToFolder.'/'.$file->getclientFilename()) ;        
                $body->$key = $uploadToFolder.'/'.$file->getclientFilename();
            }

            return $handler->handle($request->withAttribute('parsedBody', $body));
        }

        return $handler->handle($request);
    }
}