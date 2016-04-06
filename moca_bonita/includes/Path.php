<?php
namespace MocaBonita\includes;

define('plg_name'  , explode('/',  plugin_basename(__FILE__))[0]);
define('plg_view'  , plg_name . '/view/');
define('plg_js'    , plg_name . '/js/');
define('plg_css'   , plg_name . '/style/');
define('plg_images', plg_name . '/images/');
define('plg_fonts' , plg_name . '/fonts/');
define('plg_bower' , plg_name . '/bower_components/');

/**
* Find framework and plugin files.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\util
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

class Path {

	/**
    * Constant that defines the plugin directory view files
    *
    * @var string
    */
	const PLGVIEW = plg_view;

	/**
    * Constant that defines the plugin javascript files
    *
    * @var string
    */
	const PLGJS = plg_js;

	/**
    * Constant that defines the plugin directory style files
    *
    * @var string
    */
	const PLGCSS = plg_css;

	/**
    * Constant that defines the plugin directory image files
    *
    * @var string
    */
	const PLGIMAGES = plg_images;

	/**
	 * Constant that defines the plugin directory bower files
	 *
	 * @var string
	 */
	const PLGBOWER = plg_bower;
}
