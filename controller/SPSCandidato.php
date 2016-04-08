<?php
namespace SPS\controller;

use MocaBonita\controller\Controller;
use MocaBonita\includes\Validator;

class SPSCandidato extends Controller
{

    public function indexAction()
    {
        $dados = [
            'nome'   => 'jhordan lima',
            'idade'  => '18',
            'escola' => 'UNDB - Uasdas',
            'todo' => 'sdfdsfsd',
            'action' => 'sdfsdfsdfsd'
        ];

        $validator = Validator::check($dados, [
            'nome'    => 'string : 2 : 16',
            'escola'  => 'string : 10 : 16',
            'idade'   => 'numeric : 1 : 18',
        ], true);

        return $validator ? $validator : Validator::getMessages();

    }
}