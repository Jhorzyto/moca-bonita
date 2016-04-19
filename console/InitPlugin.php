<?php

namespace MocaBonita\console;


class InitPlugin
{

    public function run(array &$argv){
        echo "\nInicializar um Plugin com Moça Bonita!\n\n";

        unset($argv[0]);
        unset($argv[1]);

        $pluginNome = implode($argv, ' ');

        do{

            if(!isset($argv[2])){
                echo "Qual o nome do Plugin: ";
                $argv[2] = trim(TerminalListener::listener());
                $manterNome = 'sim';
            } else {
                echo "Deseja continuar com o {$pluginNome} para nome do Plugin?(sim/nao): ";
                $manterNome = trim(TerminalListener::listener());
                if(strtolower($manterNome) != 'sim')
                    unset($argv[2]);
                else
                    $argv[2] = $pluginNome;
            }

        } while(strtolower($manterNome) != 'sim');

        $pluginNome = ucfirst($argv[2]);

        echo "Plugin {$pluginNome} foi criado!\n";

        $this->criarDiretorio('controller');
        $this->criarDiretorio('model');
        $this->criarDiretorio('public');
        $this->criarDiretorio('public/css');
        $this->criarDiretorio('public/js');
        $this->criarDiretorio('public/images');
        $this->criarDiretorio('view');
        $this->criarDiretorio('view/exemplo');

        $this->criarComposer($pluginNome);
        $this->criarIndex($pluginNome);
        $this->criarController($pluginNome);
        $this->criarModel($pluginNome);
        $this->criarTemplate($pluginNome);
        $this->criarView($pluginNome);

        $argv[1] = "exit";

    }

    public function criarDiretorio($diretorio){
        $diretorio = './../' . $diretorio;
        if(!file_exists($diretorio)){
            mkdir($diretorio, 0775);
            echo "Diretório {$diretorio} criado!\n";
        } else
            echo "Diretório {$diretorio} já existe!\n";
    }

    public function criarComposer($pluginNome){
        $diretorio = './../composer.json';
        if(!file_exists($diretorio)) {
            $composeTemplate = file_get_contents("./console/templates/composer.mb");
            $composeTemplate = str_replace("%plugin_name%", $pluginNome, $composeTemplate);
            $composeTemplate = str_replace("%plugin_namespace%", str_replace(" ", "", $pluginNome), $composeTemplate);
            file_put_contents($diretorio, $composeTemplate);
            echo "Composer criado!\n";
        } else
            echo "Composer já existe!\n";
    }

    public function criarIndex($pluginNome){
        $diretorio = './../index.php';
        if(!file_exists($diretorio)) {
            $composeTemplate = file_get_contents("./console/templates/index.mb");
            $composeTemplate = str_replace("%plugin_name%", $pluginNome, $composeTemplate);
            $composeTemplate = str_replace("%plugin_label%", strtolower(str_replace(" ", "_", $pluginNome)), $composeTemplate);
            $composeTemplate = str_replace("%plugin_controller%", str_replace(" ", "", $pluginNome) . '\controller\ExemploController', $composeTemplate);
            file_put_contents($diretorio, $composeTemplate);
            echo "Index criado!\n";
        } else
            echo "Index já existe!\n";
    }

    public function criarController($pluginNome){
        $diretorio = './../controller/ExemploController.php';
        if(!file_exists($diretorio)) {
            $composeTemplate = file_get_contents("./console/templates/controller.mb");
            $composeTemplate = str_replace("%plugin_namespace%", str_replace(" ", "", $pluginNome), $composeTemplate);
            file_put_contents($diretorio, $composeTemplate);
            echo "Controller criado!\n";
        } else
            echo "Controller já existe!\n";
    }

    public function criarModel($pluginNome){
        $diretorio = './../model/ExemploModel.php';
        if(!file_exists($diretorio)) {
            $composeTemplate = file_get_contents("./console/templates/model.mb");
            $composeTemplate = str_replace("%plugin_namespace%", str_replace(" ", "", $pluginNome), $composeTemplate);
            file_put_contents($diretorio, $composeTemplate);
            echo "Model criado!\n";
        } else
            echo "Model já existe!\n";
    }

    public function criarTemplate($pluginNome){
        $diretorio = './../view/index.php';
        if(!file_exists($diretorio)) {
            $composeTemplate = file_get_contents("./console/templates/template.mb");
            file_put_contents($diretorio, $composeTemplate);
            echo "Template criado!\n";
        } else
            echo "Template já existe!\n";
    }

    public function criarView($pluginNome){
        $diretorio = './../view/exemplo/exemplo_view.php';
        if(!file_exists($diretorio)) {
            $composeTemplate = file_get_contents("./console/templates/view.mb");
            file_put_contents($diretorio, $composeTemplate);
            echo "View criado!\n";
        } else
            echo "View já existe!\n";
    }


}