<?php
namespace SPS\controller;
use MocaBonita\controller\Controller;
use MocaBonita\includes\Path;

class SPSController extends Controller {

    public function indexAction()
    {
        echo "fsdfdsfdsfsd";
    }

    public function requestAction(){
        return $this;
    }

    public function pathAction(){
        return [
            'plugin_name' => Path::PLGNAME,
            'paths' => [
                'plugin_view' => Path::PLGVIEW,
                'plugin_css' => Path::PLGCSS,
                'plugin_js' => Path::PLGJS,
                'plugin_images' => Path::PLGIMAGES,
                'plugin_bower' => Path::PLGBOWER,
                'plugin_path' => Path::PLGPATH,
            ]
        ];
    }

    public function getListAction(){
        $this->view->setView($this->view->getTemplate() ,'seila', 'nao');
    }

    public function loginShortcode(){
        $vars = ['message' => 'Chegou cumpade!!!'];
        $this->view->setAction('login');
        $this->view->setVars($vars);
    }

}