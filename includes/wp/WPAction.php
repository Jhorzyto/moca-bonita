<?php

namespace MocaBonita\includes\wp;
use MocaBonita\MocaBonita;

/**
* Deal with wordpress actions.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\wp
* @subpackage
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

class WPAction {

	/**
    * Add a wp action
    *
    * @param string $action Name of the action to be taken
    * @param object $object The object to treat the action
    * @param string $method The callback method
    */
	public static function addAction($action, MocaBonita $object, $method){
		add_action($action, [$object, $method]);
	}

	/**
	 * Add a wp action
	 *
	 * @param string $action Name of the action to be taken
	 * @param callback $callback The function to treat the action
     * @return true;
	 */
	public static function addActionCallback($action, $callback){
		add_action($action, $callback);
        return true;
	}

	/**
    * Do a wp action
    *
    * @param string $action The action to be taken
    */
	public static function doAction($action){
		do_action($action);
	}

}
