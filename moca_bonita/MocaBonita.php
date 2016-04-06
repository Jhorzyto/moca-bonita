<?php

namespace MocaBonita;

use MocaBonita\controller\Controller;
use MocaBonita\controller\HTTPService;
use MocaBonita\includes\MBException;
use MocaBonita\includes\TODO;
use MocaBonita\includes\WPAdminAction;
use MocaBonita\includes\wp\WPCode;
use MocaBonita\includes\wp\WPAction;
use MocaBonita\includes\wp\WPMenu;
use MocaBonita\includes\wp\WPShortCode;

/**
 * Performs the basic functions of the framework. Receives each request, passes to a controller that treats client requests and respond to them.
 *
 * @author Rômulo Batista
 * @category WordPress
 * @package moca_bonita\controller
 * @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
 */
final class MocaBonita extends HTTPService
{
    /**
     * WPMenu object
     *
     * @var object
     */
    protected $wpMenu;

    /**
     * WPCode object
     *
     * @var object
     */
    protected $wpCode;

    /**
     * WPAdminAction object
     *
     * @var object
     */
    protected $action;

    /**
     * TODO object
     *
     * @var object
     */
    protected $todo;

    /**
     * Controller object
     *
     * @var object
     */
    protected $controller;

    /**
     * Messages array
     *
     * @var array
     */
    protected $messages;

    /**
     * Plugin page boolean
     *
     * @var boolean
     */
    protected $isPluginPage;

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->wpMenu = new WPMenu();
        $this->wpCode = new WPCode();
        $this->action = WPAdminAction::singleton();
        $this->todo = new TODO();
        $this->messages = [
            'controller_not_found' => "O controller {$this->currentPage} não foi adicionado na lista de todo!",
            'controller_invalid' => "O controller {$this->currentPage} não atende aos requisitos!",
            'page_not_defined_actions' => "O todo {$this->currentPage} não tem nenhum action de post/put/delete!",
            'action_not_defined' => "O action {$this->currentAction} da página {$this->currentPage} não foi definido em action de post/put/delete!",
            'actions_without_permission' => "O action {$this->currentAction} requer acesso admin!",
            'actions_invalid_request' => "O action {$this->currentAction} requer acesso ajax!",
        ];
    }

    /**
     * Change Messages Default
     *
     */
    public function changeMessage($key, $message)
    {
        if(is_string($key) && isset($this->messages[$key]) && is_string($message))
            $this->messages[$key] = $message;
    }

    /**
     * Method launcher Plugin
     *
     */
    public function launcher()
    {
        WPAction::addAction('admin_menu', $this, 'insertMenuItems');

        $this->wpCode->addStyle('*');
        $this->wpCode->addJS('*');

        $this->wpCode->addStyle($this->currentPage);
        $this->wpCode->addJS($this->currentPage);

        if ($this->isAdmin == 1) {
            WPAction::addAction("admin_post_{$this->currentAction}", $this, 'getContent');
            WPAction::addAction("wp_ajax_{$this->currentAction}", $this, 'getContent');
        } else {
            WPAction::addAction("admin_post_nopriv_{$this->currentAction}", $this, 'getContent');
            WPAction::addAction("wp_ajax_nopriv_{$this->currentAction}", $this, 'getContent');
        }

        WPShortCode::processShortCode($this->wpCode);
    }

    /**
     * Sets the main action to this object
     *
     */
    public function getContent()
    {
        try {

            if (!$this->isPluginPage())
                return null;

            $this->controller = $this->todo->getController($this->currentPage);

            if (!$this->controller)
                throw new MBException($this->messages['controller_not_found']);

            elseif (!$this->controller instanceof Controller)
                throw new MBException($this->messages['controller_invalid']);

            elseif (!$this->isGET()) {

                $_actionsPost = $this->action->getActions($this->currentPage);

                if (!$_actionsPost)
                    throw new MBException($this->messages['page_not_defined_actions']);

                $_actionAttr = $this->action->getActionsAttr($this->currentPage, $this->currentAction);

                if (!$_actionAttr)
                    throw new MBException($this->messages['action_not_defined']);

                if ($_actionAttr['admin'] && $this->isAdmin != 1)
                    throw new MBException($this->messages['actions_without_permission']);

                elseif ($_actionAttr['type'] == 'ajax' && $this->isAjax != 1)
                    throw new MBException($this->messages['actions_invalid_request']);
            }

            $this->wpCode->addStyle('*');
            $this->wpCode->addJS('*');

            return $this->doAction();

        } catch (MBException $mb) {
            $mb->setHTTPService($this);
            return $mb->processException();
        }
    }

    /**
     * Callback for all the actions to be taken
     *
     */
    public function doAction()
    {
        $res = null;

        if ($this->isPOST())
            $res = $this->controller->postRequest($this->content);

        elseif ($this->isPUT())
            $res = $this->controller->putRequest($this->content);

        elseif ($this->isDELETE())
            $res = $this->controller->deleteRequest($this->content);

        else
            $res = $this->controller->getRequest($this->qs);

        $this->sendMessage($res);

        return null;
    }

    /**
     * Add menu items to wp admin page
     *
     * @param array $wpMenuItems The menu items array
     * @param array $wpMenuItems The submenu items array
     */
    public function addMenuItem(array $wpMenuItems, array $wpMenuSubItems)
    {
        $this->wpMenu->setMenuItems($wpMenuItems);
        $this->wpMenu->setMenuSubItems($wpMenuSubItems);
    }

    /**
     * Add menu callback method
     *
     */
    public function insertMenuItems()
    {
        if (is_admin()) {
            $this->wpMenu->addMenu();
            $this->wpMenu->addSubMenu();
        }
    }

    /**
     * Insert style files to wp
     *
     * @param array $css An array containing the style files
     */
    public function insertCSS(array $css)
    {
        $this->wpCode->setCss($css);
    }

    /**
     * Insert javascript files to wp
     *
     * @param array $js An array containing the javascript files
     */
    public function insertJS(array $js)
    {
        $this->wpCode->setJs($js);
    }

    /**
     * Insert shortcode to wp
     *
     * @param array $shortCode The shortcode name
     * @param array $object The object that will treat the shortcode
     * @param array $method The callback method
     */
    public function insertShortCode($shortCode, $todo, $method){
        $object = $this->todo->getController($todo);
        if($object instanceof Controller)
            WPShortCode::addShortCode($shortCode, $todo, $object, $method);
    }

    /**
     * Generate action posts
     *
     * @param array $actions An array containing all actions to be taken
     */
    public function generateActionPosts(array $actions = [])
    {
        $this->action->setActions($actions);
    }

    /**
     * Relates the todo to a controller
     *
     * @param string $controller The name of the controller
     * @param string $todo The todo
     */
    public function insertTODO(array $todo)
    {
        $this->todo->setTodo($todo);
    }

    /**
     * Relates the todo to a controller
     *
     * @param string $controller The name of the controller
     * @param string $todo The todo
     */
    public function isPluginPage()
    {
        if(is_null($this->isPluginPage)) {
            $_pagesAvailable = $this->wpMenu->getPagesAvailable();
            $this->isPluginPage = in_array($this->currentPage, $_pagesAvailable);
        }

        return $this->isPluginPage ? $this->currentPage : false;
    }
}
