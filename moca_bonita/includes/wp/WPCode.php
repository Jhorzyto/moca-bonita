<?php

namespace MocaBonita\includes\wp;

/**
 * Inserts links into wordpress site.
 *
 * @author Rômulo Batista
 * @category WordPress
 * @package moca_bonita\wp
 * @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
 */

class WPCode
{
    /**
     * CSS array
     *
     * @var array
     */
    private $css = [];
    /**
     * JS array
     *
     * @var array
     */
    private $js = [];

    /**
     * @return array
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @param array $css
     */
    public function setCss($page, $path)
    {
        if(!isset($this->css[$page]))
            $this->css[$page] = [];

        $this->css[$page][] = [
            'path' => $path,
            'page' => $page
        ];
    }

    /**
     * @return array
     */
    public function getJs()
    {
        return $this->js;
    }

    /**
     * @param array $js
     */
    public function setJs($page, $path, $footer)
    {
        if(!isset($this->js[$page]))
            $this->js[$page] = [];

        $this->js[$page][] = [
            'path'   => $path,
            'page'   => $page,
            'footer' => $footer
        ];
    }

    /**
     * Add a style file
     *
     * @param string $css The style URL path
     */
    public function addStyle($key)
    {
        if(isset($this->css[$key]))
            foreach ($this->css[$key] as $i => $css)
                wp_enqueue_style("style_mb_{$key}_{$i}", $css['path']);
    }

    /**
     * Add a javascript file
     *
     * @param string $js The javascript URL path
     */
    public function addJS($key)
    {
        if(isset($this->js[$key]))
            foreach ($this->js[$key] as $i => $js)
                wp_enqueue_script("script_mb_{$key}_{$i}", $js['path'], [], false, ($js['footer'] ? true : false));

    }
}
