<?php
namespace MocaBonita\includes;
use MocaBonita\service\Service;

/**
 * Deal with what to do after requests.
 *
 * Every todo in admin pages has its own action. So you need to send through query string or inside content a parameter called todo that'll be associated to an action
 *
 * @author Rômulo Batista
 * @category WordPress
 * @package moca_bonita\action
 * @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
 */

class TODO
{
    /**
     * What to do (after request)
     *
     * @var string
     */
    private $todo = [];
    private $service = [];

    /**
     * @return string
     */
    public function getTodo()
    {
        return $this->todo;
    }

    /**
     * @param string $todo
     */
    public function setTodo($todo, $class)
    {
        $this->processTodo($class, $todo);
    }

    /**
     * @return array
     */
    public function getService($page, $requestData)
    {
        if (!isset($this->service[$page]))
            return null;

        foreach ($this->service[$page] as $serviceData) {

            $serviceName = $serviceData['service'];
            $service     = new $serviceName();

            if (!$service instanceof Service)
                throw new \Exception("Service Invalid");

            foreach ($serviceData['method'] as $method){
                $methodName  = "{$method}Dispatcher";

                if(!method_exists($service, $methodName))
                    throw new \Exception("Method Invalid");

                $service->initialize($requestData);
                Service::setServicesData($serviceData, $service->{$methodName}());
            }
        }
    }

    /**
     * @param array $service
     */
    public function setService($page, $class, array $method)
    {
        if (!isset($this->service[$page]))
            $this->service[$page] = [];

        $this->service[$page][] = ['service' => $class, 'method' => $method];
    }

    /**
     * Add todo
     *
     * @param string $controller Name of the controller that will treat the request
     * @param string $todo Name of todo
     */
    private function processTodo($controller, $todo)
    {
        if (!isset($this->todo[$todo]))
            $this->todo[$todo] = ['controller' => $controller, 'todo' => $todo];
    }

    /**
     * Remove todo
     *
     * @param string $todo Name of todo
     */
    public function removeTODO($todo)
    {
        if (isset($this->todo[$todo])) {
            unset($this->todo[$todo]);
        }
    }

    /**
     * Factory of controllers
     *
     * @param string $todo What to do
     * @return Controller object for the requested todo
     */
    public function getController($todo)
    {

        if (isset($this->todo[$todo])) {
            $controllerName = $this->todo[$todo]['controller'];
            return new $controllerName();
        }

        return false;
    }
}
