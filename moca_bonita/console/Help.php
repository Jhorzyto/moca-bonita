<?php
/**
 * Created by PhpStorm.
 * User: jhordan
 * Date: 08/04/16
 * Time: 17:41
 */

namespace MocaBonita\console;


class Help
{

    static public function getHelp(){
        echo self::textColor('init'). "       plugin_name     - iniciar a estrutura de um plugin\n";
        echo self::textColor('controller'). " controller_name - iniciar a estrutura de um controller\n";
        echo self::textColor('model'). "      model_name      - iniciar a estrutura de um model\n";
        echo self::textColor('view'). "       page action     - iniciar a estrutura de um view e sua action\n";
        echo self::textColor('menu'). "       menu_title      - iniciar a estrutura de um menu wordpress\n";
        echo self::textColor('submenu'). "    submenu_title   - iniciar a estrutura de um submenu wordpress\n";
        echo self::textColor('exit', 31) . "                       - encerrar a aplicação\n";
        echo "\n\n";
    }
    static public function textColor($text, $colorCode = 32){
        return "\e[{$colorCode}m{$text}\e[0m";
    }
}