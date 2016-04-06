<?php
namespace MocaBonita\includes\wp;
/**
* Insert filter to change content into wordpress.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\wp
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

class WPContent {

	/**
    * Add a wp filter to change the content
    *
    * @param object $object The object to treat the content change
    * @param method $method The callback method
    */
	public static function ChangeContent($object, $method){
		add_filter('the_content', array($object, $method));
	}

}
