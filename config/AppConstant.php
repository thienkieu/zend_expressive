<?php
namespace Config;

abstract class AppConstant
{
    /**
     * General config
     */
    const DocumentManager = 'documentManager';
    const AppConfig       = 'config';

    /**
     * Request config
     */
    const RequestDataFieldName = 'data';
    const DTODataFieldName = 'dtoObject';

    /**
     * CORS config
     */
    const CORSConfig  = 'CORS';
    const AllowHeader = 'headers.allow';

    /**
     * Question type
     */
    const Reading = 'Reading';
    const Writing = 'Writing';
    const Listening = 'Listening';
    const Verbal = 'Verbal';
    const Other = 'Other';
    const NonSub = 'NonSub';

    /**
     * Generate type
     */
    const Pickup = 'pickup';
    const Random = 'random';


    /**
     * DTO key name
     */
    const RequestDTOName = 'dto_name';


    const Translator = 'translator';
    
    /**
     * Upload
     */
    const UploadConfigName = 'uploadConfig';
    const UploadConfigFileTypes = 'fileTypes';
    const UploadFolder = 'uploadFolder';
    const UploadSizeAllow = 'maxUploadSizeAllow';
    const UploadExtensions = 'extensions';

    const ImageExtension = 'image';
    const RadioExtensions = 'radio';

    
    /**
     * Test Service
     */
    const DTOKey = 'dto_name';
    const TestServiceTypeKey = 'testServices';
    const ResolveService = 'resolveService';

    const Log = \Zend\Log\Logger::class;

    const MediaQuestionFolder = 'MediaQuestion';
    const DS = DIRECTORY_SEPARATOR ;
    
    //external site
    const CRM = 'CRM';
    const Candidate = 'candidates';

    // pagination config
    const ItemPerPage = 25;
    const PageNumber = 1;


    const PinLength = 6;

    const DateTimeFormat = 'm/d/Y';

    const ToDocumentClass = 'toDocumentClass';
    const ShowCorrectAnswer = 'showCorrectAnswer';
    const ExistingDocument = 'existingDocument';

    const ReduceTimeSpan = 10;
    const DefaultSubQuestionMark = 2;

    const HOST_REPLACE = '%HOST%';

    const MaximunRepeateTime = 3;
    const MinimunRepeateTime = 0;

    const MarkInputTypeAuto = 'auto';
    const MarkInputTypeManual = 'manual';
    
    const AuthenticationExcludeUrl = 'authenticationExcludeUrl';    
}
