<?php
namespace MocaBonita\includes;

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

class TODO {
	/**
    * What to do (after request)
    *
    * @var string
    */
	private $todo = [];

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
    * Class constructor
    *
    */
	public function __construct(){
		//
	}

	/**
    * Add todo
    *
    * @param string $controller Name of the controller that will treat the request
    * @param string $todo Name of todo
    */
	private function processTodo($controller, $todo){
		if(!isset($this->todo[$todo]))
			$this->todo[$todo] = ['controller' => $controller, 'todo' => $todo];
	}

	/**
    * Remove todo
    *
    * @param string $todo Name of todo
    */
	public function removeTODO($todo){
		if(isset($this->todo[$todo])){
			unset($this->todo[$todo]);
		}
	}

	/**
    * Factory of controllers
    *
    * @param string $todo What to do
    * @return Controller object for the requested todo
    */
	public function getController($todo){

		if(isset($this->todo[$todo])) {
			$controllerName = $this->todo[$todo]['controller'];
			return new $controllerName();
		}

		return false;
	}
}
