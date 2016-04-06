<?php

namespace MocaBonita\controller;

use MocaBonita\includes\HTTPMethod;
use MocaBonita\includes\json\JSONService;

/**
* Service to treat request/response requests.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\controller
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

abstract class HTTPService {
    /**
    * Query string
    *
    * @var string
    */
    protected $qs;
    /**
    * POST, PUT or DELETE content body
    *
    * @var array
    */
    protected $content;
    /**
    * HTTP request method
    *
    * @var string
    */
    protected $requestMethod;
    /**
    * JSONService object
    *
    * @var object
    */
    protected $json;

    /**
     * Current Page string
     *
     * @var string
     */
    public $currentPage;

    /**
     * Current Action string
     *
     * @var string
     */
    public $currentAction;

    /**
     * Current Page Type boolean
     *
     * @var boolean
     */
    public $isAdmin;

    /**
     * Current Request Type boolean
     *
     * @var boolean
     */
    public $isAjax;

    /**
    * Class constructor
    *
    */
    public function __construct(){
        $this->json = new JSONService();
        $this->getRequest();
        $this->currentPage   = isset($_GET['page'])   ? $_GET['page']   : 'no_page';
        $this->currentAction = isset($_GET['action']) ? $_GET['action'] : null;
        $this->currentAction = $this->isGET()  && is_null($_GET['action']) ? 'index'     : $this->currentAction;
        $this->currentAction = !$this->isGET() && is_null($_GET['action']) ? 'no_action' : $this->currentAction;
        $this->isAdmin       = is_admin() && is_user_logged_in();
        $this->isAjax        = defined('DOING_AJAX') && DOING_AJAX;
    }

    /**
    * Get the request from client
    *
    */
    public function getRequest(){
        if($this->isGET())
            $this->qs = $_GET;

        if($this->isPOST()){
            $post = file_get_contents('php://input');

            if($this->isJSON($post))
                $_POST = json_decode($post, true);

            $this->content = $_POST;
        }

        if($this->isPUT()){
            $put = file_get_contents("php://input");

            if($this->isJSON($put))
                $this->content = $this->json->decode($put);
            else
                $this->content = $put;
        }

        if($this->isDELETE()){
            $delete = file_get_contents("php://input");

            if($this->isJSON($delete))
                $this->content = $this->json->decode($delete);
            else
                $this->content = $delete;
        }
    }

    /**
    * Set the request method
    *
    */
    public function setRequestMethod(){
        if(isset($_REQUEST))
            $this->request = $_REQUEST;
    }

    /**
    * Check if it's a GET method
    *
    * @return True if the method is GET, false if it's not
    */
    public function isGET(){
        if($_SERVER['REQUEST_METHOD'] === 'GET')
            return true;

        return false;
    }

    /**
    * Check if it's a POST method
    *
    * @return True if the method is POST, false if it's not
    */
    public function isPOST(){
        if($_SERVER['REQUEST_METHOD'] === 'POST')
            return true;

        return false;
    }

    /**
    * Check if it's a PUT method
    *
    * @return True if the method is PUT, false if it's not
    */
    public function isPUT(){
        if($_SERVER['REQUEST_METHOD'] === 'PUT')
            return true;

        return false;
    }

    /**
    * Check if it's a DELETE method
    *
    * @return True if the method is DELETE, false if it's not
    */
    public function isDELETE(){
        if($_SERVER['REQUEST_METHOD'] === 'DELETE')
            return true;

        return false;
    }

    /**
    * Check if something was requested
    *
    * @return True if something was requested, false if was not
    */
    public function isREQUEST(){
        if(isset($this->request))
            return true;

        return false;
    }

    /**
    * Check if the string is in JSON format
    *
    * @param string $str A string
    * @return True if the method is GET, false if it's not
    */
    public function isJSON($str){
        return $this->json->isJSON($str);
    }

    /**
     * Check if the request is in Ajax
     *
     * @return True if the request is Ajax, false if it's not
     */
    public function isAjax(){
        return $this->isAjax == 1;
    }

    /**
    * Send the message back to the client
    *
    * @param array or string $msg The response message
    * @return A message in JSON or TEXT
    */
    public function sendMessage($msg){
        if(is_array($msg))
            $this->json->sendJSON($msg, $this);

        elseif(is_null($msg) && $this->isAjax())
            $this->json->sendJSON(HTTPMethod::getError(HTTPMethod::NO_CONTENT, 'Nenhum dado foi retornado!'), $this);
    }

}
