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
    exit('O acesso direto não é permitido para esse plugin!');

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

        //Configurações do MENU
        $_wpMenu = [
            [
                'SPS Uema',
                'SPS Uema',
                'read',
                'sps_uema',
                $_mocaBonita,
                'getContent',
                'dashicons-welcome-widgets-menus',
                2,
            ]
        ];

        //Configurações do SubMenu
        $_wpSubMenu = [
            [
                'sps_uema',
                'Candidatos',
                'Candidatos',
                'read',
                'sps_candidato',
                $_mocaBonita,
                'getContent',
            ],
            [
                'sps_uema',
                'Teste',
                'Teste',
                'read',
                'sps_teste',
                $_mocaBonita,
                'getContent',
            ],
        ];

        //Incluir menu e submenu ao wordpress
        $_mocaBonita->addMenuItem($_wpMenu, $_wpSubMenu);

        //Configurações do CSS
        $_css = [
            [
                'path' => '/sps/Bootflat/css/bootstrap.min.css',
                'page' => 'plugin'
            ],
            [
                'path' => '/sps/Bootflat/bootflat/css/bootflat.min.css',
                'page' => 'plugin',
            ],
        ];

        //Configurações do JS
        $_js = [
            [
                'path'   => '/sps/Bootflat/js/jquery-1.10.1.min.js',
                'page'   => 'sps_uema',
                'footer' => !$_mocaBonita->isAdmin
            ],
            [
                'path'   => '/sps/Bootflat/js/bootstrap.min.js',
                'page'   => 'sps_candidato',
                'footer' => !$_mocaBonita->isAdmin,
            ],
        ];

        //Inserir CSS e JS ao wordpress
        $_mocaBonita->insertCSS($_css);
        $_mocaBonita->insertJS($_js);

        //Configurações dos actions post/put/delete
        $_actionPost = [
            'sps_uema' => [
                [
                    'action'  => 'create',
                    'type'    => 'ajax',
                    'admin'   => false,
                    'request' => 'POST',
                ],
                [
                    'action'  => 'path',
                    'type'    => 'ajax',
                    'admin'   => false,
                    'request' => 'GET',
                ],
                [
                    'action'  => 'request',
                    'type'    => 'html',
                    'admin'   => false,
                    'request' => '*',
                ],
                [
                    'action'  => 'getList',
                    'type'    => 'html',
                    'admin'   => false,
                    'request' => '*',
                ],
            ],
            'sps_teste' => [
                [
                    'action'  => 'create',
                    'type'    => 'html',
                    'admin'   => false,
                    'request' => 'put',
                ],
            ]
        ];

        //Adicionar os actionsPost ao wordpress
        $_mocaBonita->generateActionPosts($_actionPost);

        //Configurações de controller atribuidos aos seus respectivos todos
        $_todo = [
            'sps_uema' => '\SPS\controller\SPSController',
            'sps_candidato' => '\SPS\controller\SPSCandidato',
            'sps_teste' => '\SPS\controller\SPSTeste',
        ];

        $_mocaBonita->insertTODO($_todo);

        $_mocaBonita->insertShortCode('moca_bonita', 'sps_teste', 'login');

        $_mocaBonita->launcher();

    } catch (\Exception $e){
        echo $e->getMessage();
    }
});
