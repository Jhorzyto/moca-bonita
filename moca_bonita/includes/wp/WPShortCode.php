<?php
namespace MocaBonita\includes\wp;
use MocaBonita\controller\Controller;

/**
* Insert wordpress shortcode.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\wp
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

class WPShortCode {

    private static $shortCodes = [];

	/**
    * Add a shortcode to wp
    *
    * @param string $shortCode The shortcode to be called
    * @param string $todo The object name to treat the shortcode call
    * @param Controller $object The object to treat the shortcode call
    * @param string $method The callback method
    */

	public static function addShortCode($shortCode, $todo, Controller $object, $method){
		self::$shortCodes[] = [
			'shortcode' => $shortCode,
			'todo'      => $todo,
            'object'    => $object,
			'method'    => $method,
		];
	}

    public static function processShortCode(WPCode $wpCode){
        foreach(self::$shortCodes as &$shortCode)
            add_shortcode($shortCode['shortcode'], function() use ($shortCode, $wpCode){
                $wpCode->addStyle($shortCode['todo']);
                $wpCode->addJS($shortCode['todo']);
                $shortCode['object']->$shortCode['method']();
            });
    }

}
