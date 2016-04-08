<?php
namespace MocaBonita\console;

class TerminalListener {

    static public function listener(){
        $var_stdin   = fopen('php://stdin', 'r');
        $var_palavra = fgets($var_stdin);
        fclose($var_stdin);
        return $var_palavra;
    }

}