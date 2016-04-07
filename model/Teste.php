<?php
namespace SPS\model;

use MocaBonita\model\ModelMB;

class Teste extends ModelMB {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'wp_posts';
    }

}