<?php
namespace MocaBonita\includes;

use MocaBonita\includes\wp\WPAction;
use MocaBonita\MocaBonita;

/**
* Deal with actions that'll be performed.
*
* Every action performed or requested in admin pages has its own todo. Every post action through will be treaten based on the chosen todo, that will be treated with the controller chose.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\action
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

class WPAdminAction {
	/**
    * Instance of the class
    *
    * @var string
    */
	private static $instance;
	/**
    * Actions to be taken
    *
    * @var string
    */
	private $actions;

	/**
	 * @return string
	 */
	public function getActions($todo)
	{
		return isset($this->actions[$todo]) ? $this->actions[$todo] : false ;
	}
	/**
	 * @return string
	 */
	public function getActionsAttr($todo, $action)
	{
		if(!isset($this->actions[$todo]))
			return false;

		foreach($this->actions[$todo] as $actPost){
			if($action == $actPost['action'])
				return $actPost;
		}

		return false;
	}

	/**
	 * @param string $actions
	 */
	public function setActions($actions)
	{
		$this->actions = $actions;
	}

	/**
    * Class constructor
    *
    */
	private function __construct(){
		$this->actions = array();
	}

	/**
    * Clone cannot be allowed
    *
    */
	public function __clone(){
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    /**
    * Retrieve a unique instance
    * @return WPAdminAction
    */
	public static function singleton(){
		if(!isset(self::$instance)){
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	/**
    * Add actions to wp
    *
    * @param array $actions The array of actions to be performed
    * @param object $main The main object that will treat the actions
    */
	public function addAction($action, MocaBonita $main, $type, $isAdmin){
        if($type == 'ajax' && $isAdmin)
            WPAction::addAction("wp_ajax_{$action}", $main, 'doAction');

        elseif($type == 'ajax' && !$isAdmin)
            WPAction::addAction("wp_ajax_nopriv_{$action}", $main, 'doAction');

        elseif($type != 'ajax' && $isAdmin)
            WPAction::addAction("admin_post_{$action}", $main, 'doAction');

        else
            WPAction::addAction("admin_post_nopriv_{$action}", $main, 'doAction');
	}

}
