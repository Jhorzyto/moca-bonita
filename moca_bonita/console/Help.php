<?php
namespace MocaBonita\console;

class Help
{

    public function getHelp(){
        echo "\nMais sobre o Abigail Moça Bonita\n\n";
        echo $this->textColor('init'). " plugin_name - iniciar a estrutura de um plugin\n";
        echo "\n\n";
    }

    public function textColor($text, $colorCode = 32){
        return "\e[{$colorCode}m{$text}\e[0m";
    }

    public function run(array &$argv){
        $this->getHelp();
        echo "Escolha uma opção: ";
        $argv[1] = trim(TerminalListener::listener());
    }
}