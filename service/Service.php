<?php

namespace MocaBonita\service;

/**
 * Class for Moça Bonita services.
 *
 * @author Jhordan Lima
 * @category WordPress
 * @package moca_bonita\service
 * @copyright Copyright (c) 2016
 */

abstract class Service {

    protected $requestMethod;
    protected $requestData;
    protected $requestParams;
    protected $currentPage;
    protected $currentAction;
    protected $isAdmin;
    protected $isAjax;
    private static $servicesData = [];

    public function __construct(){
        $this->requestMethod = 'GET';
        $this->requestData = [];
        $this->requestParams = [];
        $this->currentPage = 'no_page';
        $this->currentAction = 'no_action';
        $this->isAdmin = false;
        $this->isAjax = false;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param string $requestMethod
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return array
     */
    public function getRequestData($key = null)
    {
        if(is_null($key))
            return $this->requestData;
        elseif(isset($this->requestData[$key]))
            return $this->requestData[$key];
        else
            return [];
    }

    /**
     * @param array $requestData
     */
    public function setRequestData($requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @return array
     */
    public function getRequestParams($key = null)
    {
        if(is_null($key))
            return $this->requestParams;
        elseif(isset($this->requestParams[$key]))
            return $this->requestParams[$key];
        else
            return null;
    }

    /**
     * @param array $requestParams
     */
    public function setRequestParams($requestParams)
    {
        $this->requestParams = $requestParams;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param mixed $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return string
     */
    public function getCurrentAction()
    {
        return $this->currentAction;
    }

    /**
     * @param string $currentAction
     */
    public function setCurrentAction($currentAction)
    {
        $this->currentAction = $currentAction;
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param boolean $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return boolean
     */
    public function isAjax()
    {
        return $this->isAjax;
    }

    /**
     * @param boolean $isAjax
     */
    public function setIsAjax($isAjax)
    {
        $this->isAjax = $isAjax;
    }

    public final function initialize($data){
        foreach ($data as $method => $value)
            $this->{$method}($value);
    }

    protected function redirect($url, array $params = []){
        if(is_string($url)){
            $url .= !empty($params) ? "?" . http_build_query($params) : "";
            header("Location: {$url}");
            exit();
        }
    }

    /**
     * @return mixed
     */
    public static function getServicesData()
    {
        return self::$servicesData;
    }

    /**
     * @param mixed $servicesData
     */
    public static function setServicesData(array $service, $servicesData)
    {
        $service['service_data'] = $servicesData;
        self::$servicesData[]    = $service;
    }

}
