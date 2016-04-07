<?php
namespace MocaBonita\includes;

define('plg_name'  , explode('/',  plugin_basename(__FILE__))[0]);
define('plg_path'  , WP_PLUGIN_DIR . "/" . plg_name);
define('plg_url'   , WP_PLUGIN_URL . "/" . plg_name);
define('plg_view'  , plg_path . '/view/');
define('plg_js'    , plg_url  . '/public/js/');
define('plg_css'   , plg_url  . '/public/css/');
define('plg_images', plg_url  . '/public/images/');
define('plg_fonts' , plg_url  . '/public/fonts/');
define('plg_bower' , plg_url  . '/public/bower_components/');

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
    * Constant that defines the plugin name files
    *
    * @var string
    */
	const PLGNAME = plg_name;

	/**
	 * Constant that defines the plugin directory path
	 *
	 * @var string
	 */
	const PLGPATH = plg_path;

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
