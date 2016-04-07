<?php
namespace MocaBonita\includes\wp;
use MocaBonita\controller\Controller;
use MocaBonita\view\View;

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

    public static function processShortCode(WPCode $wpCode, array $plugin){
        foreach(self::$shortCodes as &$shortCode)
            add_shortcode($shortCode['shortcode'], function($atts, $content, $tag) use ($shortCode, $wpCode, $plugin){
                $wpCode->addStyle('plugin');
                $wpCode->addJS('plugin');
                $wpCode->addStyle($shortCode['todo']);
                $wpCode->addJS($shortCode['todo']);
                $page = $shortCode['method'];
                $shortCode['method'] = "{$shortCode['method']}Shortcode";

                if(method_exists($shortCode['object'], $shortCode['method'])){
                    $shortCode['object']->setRequestMethod($plugin['requestMethod']);
                    $shortCode['object']->setRequestParams($plugin['requestParams']);
                    $shortCode['object']->setIsAdmin($plugin['isAdmin']);
                    $shortCode['object']->setIsShortcode(true);
                    $shortCode['object']->getView()->setTemplate('shortcode');
                    $shortCode['object']->getView()->setPage('shortcode');
                    $shortCode['object']->getView()->setAction($page);

                    ob_start();
                    $res = $shortCode['object']->$shortCode['method']($atts, $content, $tag);
                    $_content = ob_get_contents();
                    ob_end_clean();

                    if($_content != "")
                        error_log($_content);

                    $res = is_null($res) ? $shortCode['object']->getView() : $res;

                    if($res instanceof View)
                        $res->render();
                    elseif(is_string($res))
                        echo $res;
                    else
                        echo "Nenhum conteudo foi retornado!";

                } else {
                    echo $plugin['messages']['invalid_shortcode'];
                    echo "<br>";
                    if($plugin['isDevelopment'])
                        echo "Shortcode: {$shortCode['shortcode']}; Method: {$shortCode['method']}; Todo: {$shortCode['todo']}.";
                }


            });
    }

}
