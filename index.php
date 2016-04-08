<?php
/*
    Plugin Name: SPS Uema
    Plugin URI: http://exemplo.org/o-meu-plugin
    Description: Um plugin de teste didático
    Version: 1.0
    Author: Aluno da Escola WordPress
    Author URI: http://exemplo.org
    License: GPLv2
*/

//Verificar se o plugin esta sendo acessado através do wordpress
if(!defined('ABSPATH'))
    exit('O acesso direto não é permitido para esse plugin!\n');

//Inicializar o plugin após o wordpress definir
add_action('plugins_loaded', function(){
    try{

        //Caminho do composer autoload
        $_autoload = plugin_dir_path(__FILE__) . "vendor/autoload.php";

        //Verificar se existe o autoload
        if(!file_exists($_autoload))
            throw new Exception('O composer autoload não foi instalado no plugin!');

        //Incluir o autoload
        require_once $_autoload;

        //Instanciar o Moca Bonita
        $_mocaBonita = new \MocaBonita\MocaBonita(true);

        //Incluir menu e submenu ao wordpress
        $_mocaBonita->addMenuItem('SPS Uema', 'read', 'sps_uema', 'dashicons-welcome-widgets-menus', 2);

        $_mocaBonita->addMenuItem('Plugin Teste', 'read', 'plugin_teste', 'dashicons-welcome-widgets-menus', 2);

        $_mocaBonita->addSubMenuItem('Candidatos', 'read', 'sps_candidato', 'sps_uema');
        $_mocaBonita->addSubMenuItem('Teste', 'read', 'sps_teste', 'sps_uema');

        //Inserir CSS e JS ao wordpress
        $_mocaBonita->insertCSS('plugin', '/sps/Bootflat/css/bootstrap.min.css');
        $_mocaBonita->insertCSS('plugin', '/sps/Bootflat/bootflat/css/bootflat.min.css');

        $_mocaBonita->insertJS('sps_uema', '/sps/Bootflat/js/jquery-1.10.1.min.js');
        $_mocaBonita->insertJS('sps_candidato', '/sps/Bootflat/js/bootstrap.min.js', !$_mocaBonita->isAdmin);

        //Adicionar os actionsPost ao wordpress
        $_mocaBonita->generateActionPosts('sps_uema', 'create');
        $_mocaBonita->generateActionPosts('sps_uema', 'path', false, 'GET', 'ajax');
        $_mocaBonita->generateActionPosts('sps_uema', 'request', false, '*');
        $_mocaBonita->generateActionPosts('sps_uema', 'getList', false, '*');
        $_mocaBonita->generateActionPosts('sps_teste', 'create', false, 'PUT');

        $_mocaBonita->generateActionPosts('plugin_teste', 'create', false, 'GET', 'http');

        //Adicionar os 'Todos' ao wordpress
        $_mocaBonita->insertTODO('sps_uema', '\SPS\controller\SPSController');
        $_mocaBonita->insertTODO('sps_candidato', '\SPS\controller\SPSCandidato');
        $_mocaBonita->insertTODO('sps_teste', '\SPS\controller\SPSTeste');
        $_mocaBonita->insertTODO('plugin_teste', '\SPS\controller\PluginTesteController');

        //Adicionar shortcode ao wordpress
        $_mocaBonita->insertShortCode('sps_teste', 'moca_bonita', 'login');

        //Lançar o plugin para o wordpress
        $_mocaBonita->launcher();

    } catch (\Exception $e){
        //Exibir exceção
        echo $e->getMessage();
    }
});
