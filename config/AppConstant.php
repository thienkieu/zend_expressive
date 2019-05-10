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
     * CORS config
     */
    const CORSConfig  = 'CORS';
    const AllowHeader = 'headers.allow';

    const Reading = 'reading';
    const Writing = 'writing';
    const Listening = 'listening';
    const RequestDTOName = 'dto_name';


    const Translator = 'translator';
    
    const UploadConfigName = 'uploadConfig';
    const UploadConfigFileTypes = 'fileTypes';
    const UploadFolder = 'uploadFolder';
    const UploadSizeAllow = 'maxUploadSizeAllow';
}
