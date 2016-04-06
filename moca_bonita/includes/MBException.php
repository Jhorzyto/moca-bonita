<?php

namespace MocaBonita\includes;

use MocaBonita\controller\HTTPService;
use MocaBonita\includes\json\JSONService;

class MBException extends \Exception
{
    /**
     * HTTPService object
     *
     * @var object
     */
    private $HTTPService;

    public function getHTTPService()
    {
        return $this->HTTPService;
    }

    public function setHTTPService(HTTPService $HTTPService)
    {
        $this->HTTPService = $HTTPService;
    }

    public function processException(){
        if($this->HTTPService->isAjax())
            JSONService::sendJSON(HTTPMethod::getError(HTTPMethod::REQUEST_UNAVAIABLE, $this->getMessage()), $this->HTTPService);
        else
            echo "<div class='notice notice-error'><p>{$this->getMessage()}</p></div>";
    }
}