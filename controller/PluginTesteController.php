<?php
namespace SPS\controller;

use MocaBonita\controller\Controller;
use SPS\model\Teste;

class PluginTesteController extends Controller
{

    public function indexAction()
    {
        return (new Teste())->getAll(['post_title', 'post_content'], ['ID', 1]);
    }

}