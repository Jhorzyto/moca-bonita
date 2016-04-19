<?php
namespace MocaBonita\view;

use MocaBonita\includes\Path;

class View {

    protected $template;
    protected $page;
    protected $action;
    protected $vars;
    protected $content;
    private $path;

    public function __construct()
    {
        $this->vars = [];
        $this->content = "";
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        if(is_string($template))
            $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        if(is_string($page))
            $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        if(is_string($action))
            $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param mixed $vars
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $template
     * @param string $page
     * @param string $action
     * @param array $vars
     * @return View
     */
    public function setView($template, $page, $action, array $vars = []){
        $this->setTemplate($template);
        $this->setPage($page);
        $this->setAction($action);
        $this->setVars($vars);
        return $this;
    }

    private function processPath($type = 'action'){
        if($type == 'action')
            $this->path = Path::PLGVIEW . "{$this->page}/{$this->action}.php";
        else
            $this->path = Path::PLGVIEW . "{$this->template}.php";
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->processPath();

        foreach($this->vars as $attr => $value)
            $$attr = $value;

        if(file_exists($this->path)){
            ob_start();
            include $this->path;
            $page = ob_get_contents();
            ob_end_clean();
        } else
            $page = "<div class='notice notice-error'><p>O arquivo <strong>{$this->path}</strong> não foi encontrado!</p></div>";

        $this->setContent($page);
        return $this->applyView();
    }

    private function applyView(){

        $this->processPath('template');

        if(file_exists($this->path)){
            ob_start();
            include $this->path;
            $page = ob_get_contents();
            ob_end_clean();
        } else
            $page = "<div class='notice notice-error'><p>O arquivo <strong>{$this->path}</strong> de template não foi encontrado!</p></div>";

        echo $page;
        return true;
    }

}