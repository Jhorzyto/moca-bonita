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
     * Development page boolean
     *
     * @var boolean
     */
    public $isDevelopment;


    /**
     * Class constructor
     * @param boolean $isDevelopment Development page boolean
     *
     */
    public function __construct($isDevelopment = false)
    {
        parent::__construct();
        $this->wpMenu = new WPMenu();
        $this->wpCode = new WPCode();
        $this->action = WPAdminAction::singleton();
        $this->todo = new TODO();
        $this->isDevelopment = $isDevelopment;

        if($isDevelopment)
            $this->messages = [
                'controller_not_found' => "O controller {$this->currentPage} não foi adicionado na lista de todo!",
                'controller_invalid' => "O controller {$this->currentPage} não extendeu a controller do Moca Bonita!",
                'page_not_defined_actions' => "O todo {$this->currentPage} não tem nenhum action de get/post/put/delete!",
                'action_not_defined' => "O action {$this->currentAction} da página {$this->currentPage} não foi definido em action de get/post/put/delete!",
                'actions_without_permission' => "O action {$this->currentAction} requer acesso admin do wordpress!",
                'actions_invalid_type' => "O action {$this->currentAction} precisa ser requisitado via ajax!",
                'actions_invalid_request' => "O action {$this->currentAction} não permite esse tipo de requisição http!",
                'actions_not_found' => "O action {$this->currentAction}Action não existe no controller do todo {$this->currentPage}!",
                'invalid_shortcode' => "A action desse shortcode é inválida!",
            ];
        else
            $this->messages = [
                'controller_not_found' => "Esta pagina nao foi definida!",
                'controller_invalid' => "Esta pagina nao possui a configuraçao recomendada!",
                'page_not_defined_actions' => "As açoes dessa pagina nao foram definidas",
                'action_not_defined' => "Esta açao nao foi definida para esta pagina",
                'actions_without_permission' => "Voce precisa esta logado para acessar esta açao!",
                'actions_invalid_type' => "Esta requisiçao precisa ser executada via Ajax!",
                'actions_invalid_request' => "Esta requisiçao nao e permitida para esta açao",
                'actions_not_found' => "Esta açao nao foi encontrada!",
                'invalid_shortcode' => "Esta ação nao foi encontrada!",
            ];

    }

    /**
     * Change Messages Default
     *
     */
    public function changeMessage($key, $message)
    {
        if(is_string($key) && is_string($message))
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

        if($this->isPluginPage()){
            $this->wpCode->addStyle('plugin');
            $this->wpCode->addJS('plugin');
        }

        $this->wpCode->addStyle($this->currentPage);
        $this->wpCode->addJS($this->currentPage);

        if ($this->isAdmin == 1) {
            if($this->isAjax == 1)
                WPAction::addAction("wp_ajax_{$this->currentAction}", $this, 'getContent');
            else
                WPAction::addAction("admin_post_{$this->currentAction}", $this, 'getContent');
        } else {
            if($this->isAjax == 1)
                WPAction::addAction("wp_ajax_nopriv_{$this->currentAction}", $this, 'getContent');
            else
                WPAction::addAction("admin_post_nopriv_{$this->currentAction}", $this, 'getContent');
        }

        WPShortCode::processShortCode($this->wpCode, [
            'requestMethod' => $this->requestMethod,
            'requestParams' => $this->qs,
            'isAdmin' => $this->isAdmin,
            'messages' => $this->messages,
            'isDevelopment' => $this->isDevelopment,
        ]);
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

            elseif (!$this->isGET() || $this->currentAction != 'index') {

                $_actionsPost = $this->action->getActions($this->currentPage);

                if (!$_actionsPost)
                    throw new MBException($this->messages['page_not_defined_actions']);

                $_actionAttr = $this->action->getActionsAttr($this->currentPage, $this->currentAction);

                if (!$_actionAttr)
                    throw new MBException($this->messages['action_not_defined']);

                $_actionAttr['action']  = "{$this->currentAction}Action";

                if ($_actionAttr['admin'] && $this->isAdmin != 1)
                    throw new MBException($this->messages['actions_without_permission']);

                elseif ($_actionAttr['type'] == 'ajax' && $this->isAjax != 1)
                    throw new MBException($this->messages['actions_invalid_type']);

                elseif($_actionAttr['request'] != $this->requestMethod && $_actionAttr['request'] != '*')
                    throw new MBException($this->messages['actions_invalid_request']);

                elseif(!method_exists($this->controller, $_actionAttr['action']))
                    throw new MBException($this->messages['actions_not_found']);

            } else
                $_actionAttr = [
                    'action'  => 'indexAction',
                    'type'    => 'html',
                    'admin'   => true,
                    'request' => 'GET',
                ];

            $this->controller->setRequestMethod($this->requestMethod);
            $this->controller->setRequestData($this->content);
            $this->controller->setRequestParams($this->qs);
            $this->controller->setCurrentPage($this->currentPage);
            $this->controller->setCurrentAction($this->currentAction);
            $this->controller->setIsAdmin($this->isAdmin);
            $this->controller->setIsAjax($this->isAjax);
            $this->controller->getView()->setPage($this->currentPage);
            $this->controller->getView()->setAction($this->currentAction);

            ob_start();
            $res = $this->controller->$_actionAttr['action']();
            $_content = ob_get_contents();
            ob_end_clean();

            if($_content != "")
                error_log($_content);

            $res = !$this->isAjax && is_null($res) ? $this->controller->getView() : $res;

            $this->sendMessage($res);
            return null;

        } catch (MBException $mb) {
            $mb->setHTTPService($this);
            return $mb->processException();
        } catch (\Exception $e) {
            $mb = new MBException($e->getMessage());
            $mb->setHTTPService($this);
            return $mb->processException();
        }
    }

    /**
     * Add menu items to wp admin page
     *
     * @param array $wpMenuItems The menu items array
     * @param array $wpMenuItems The submenu items array
     */
    public function addMenuItem($menuTitle, $capability, $menuSlug, $icon, $position = 100, $removeSubmenu = true)
    {
        $this->wpMenu->setMenuItems([
            'menu_title'     => $menuTitle,
            'capability'     => $capability,
            'menu_slug'      => $menuSlug,
            'object'         => $this,
            'method'         => 'getContent',
            'icon'           => $icon,
            'position'       => $position,
            'remove_submenu' => $removeSubmenu
        ]);
    }

    /**
     * Add menu items to wp admin page
     *
     * @param array $wpMenuItems The menu items array
     * @param array $wpMenuItems The submenu items array
     */

    public function addSubMenuItem($menuTitle, $capability, $menuSlug, $parentSlug = '')
    {
        $this->wpMenu->setMenuSubItems([
            'parent_slug' => $parentSlug,
            'menu_title'  => $menuTitle,
            'capability'  => $capability,
            'menu_slug'   => $menuSlug,
            'object'      => $this,
            'method'      => 'getContent',
        ]);
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
    public function insertCSS($page, $path)
    {
        $this->wpCode->setCss($page, $path);
    }

    /**
     * Insert javascript files to wp
     *
     * @param array $js An array containing the javascript files
     */
    public function insertJS($page, $path, $footer = false)
    {
        $this->wpCode->setJs($page, $path, $footer);
    }

    /**
     * Insert shortcode to wp
     *
     * @param string $shortCode The shortcode name
     * @param string $todo The object that will treat the shortcode
     * @param string $method The callback method
     */
    public function insertShortCode($todo, $shortCode, $method){
        $object = $this->todo->getController($todo);
        if($object instanceof Controller)
            WPShortCode::addShortCode($shortCode, $todo, $object, $method);
    }

    /**
     * Generate action posts
     *
     * @param array $actions An array containing all actions to be taken
     */
    public function generateActionPosts($page, $action, $admin = true, $request = 'GET', $type = 'html')
    {
        $this->action->setActions($page, $action, $admin, $request, $type);
    }

    /**
     * Relates the todo to a controller
     *
     * @param array $todo The todo
     */
    public function insertTODO($todo, $class)
    {
        $this->todo->setTodo($todo, $class);
    }

    /**
     * Relates the todo to a controller
     *
     */
    public function isPluginPage()
    {
        if(is_null($this->isPluginPage)) {
            $_pagesAvailable    = $this->wpMenu->getPagesAvailable();
            $this->isPluginPage = in_array($this->currentPage, $_pagesAvailable);
        }

        return $this->isPluginPage;
    }
}
