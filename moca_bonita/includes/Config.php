<?php
namespace MocaBonita\includes;
/**
* Deal with config file in pairs (parameter = value).
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\util
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

class Config {
	/**
    * Variables
    *
    * @var array
    */
	private $vars;

	/**
    * Class constructor
    *
    */
	public function __construct(){
		$this->vars = array();
		$this->readConf();
	}

	/**
    * Magic method __get
    *
    * @param $field Name of field
    * @return The respective parameter
    */
	public function __get($field){
        if(is_string($field) && isset($this->vars[$field]))
        	return $this->vars[$field];
        return null;
	}

	/**
    * Read the config file and insert the parametes in array $vars
    *
    */
	public function readConf(){
		$path = "web-config.conf";

		if(file_exists($path)) {
			$data = parse_ini_file($path, true);
            foreach($data as $key => $item)
                $this->vars[$key] = $item;
		}
	}
}
