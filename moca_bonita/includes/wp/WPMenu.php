<?php
namespace MocaBonita\includes\wp;
/**
* Insert and remove wordpress admin menus.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\wp
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

class WPMenu {
	/**
    * Array with the menu itens
    *
    * @var array
    */

	private $wpMenuItems;
	/**
    * Array with the submenu itens
    *
    * @var array
    */
	private $wpMenuSubItems;

    /**
     * Array with the pages available
     *
     * @var array
     */
    private $wpPagesAvailable;

	/**
    * Class constructor
    *
    */
	public function __construct(){
		$this->wpMenuItems      = array();
		$this->wpMenuSubItems   = array();
        $this->wpPagesAvailable = array();
	}

	/**
    * Set menu items
    *
    * @param array $wpMenuItems An array with items
    */
	public function setMenuItems(array $wpMenuItems){
		$this->wpMenuItems[]      = $wpMenuItems;
        $this->wpPagesAvailable[] = $wpMenuItems[3];
	}

    /**
     * Get pages available
     *
     * @return array
     */
    public function getPagesAvailable(){
        return $this->wpPagesAvailable;
    }

	/**
    * Get menu items
    *
    * @return The menu items
    */
	public function getMenuItems(){
		return $this->wpMenuItems;
	}

	/**
    * Unset menu items from memory
    *
    */
	public function freeMenuItems(){
		unset($this->wpMenuItems);
	}

	/**
    * Set menu sub items
    *
    * @param array $wpMenuSubItems An array with items
    */
	public function setMenuSubItems(array $wpMenuSubItems){
		$this->wpMenuSubItems[] = $wpMenuSubItems;
        $this->wpPagesAvailable[] = $wpMenuSubItems[4];
	}

	/**
    * Get menu subitems
    *
    * @return The menu subitems
    */
	public function getMenuSubItems(){
		return $this->wpMenuSubItems;
	}

	/**
    * Unset menu subitems from memory
    *
    */
	public function freeSubMenuItems(){
		unset($this->wpSubMenuItems);
	}

	/**
    * Add a set of menus to wp admin page
    *
    */
	public function addMenu(){
		foreach($this->wpMenuItems as &$wpMenuItem)
            $this->addWPMenu(
                $wpMenuItem[0],
                $wpMenuItem[1],
                $wpMenuItem[2],
                $wpMenuItem[3],
                $wpMenuItem[4],
                $wpMenuItem[5],
                $wpMenuItem[6],
                isset($wpMenuItem[7]) ? $wpMenuItem[7] : 100
            );
	}

	/**
    * Add a set of submenus from a menu to wp admin page
    *
    */
	public function addSubMenu(){
        foreach($this->wpMenuSubItems as &$wpMenuSubItem)
            $this->addWPSubMenu(
                $wpMenuSubItem[0],
                $wpMenuSubItem[1],
                $wpMenuSubItem[2],
                $wpMenuSubItem[3],
                $wpMenuSubItem[4],
                $wpMenuSubItem[5],
                $wpMenuSubItem[6]
            );
	}

	/**
    * Add menu to wp admin page
    *
    * @param string $pageTitle The text to be displayed in the title tags of the page when the menu is selected
    * @param string $menuTitle The on-screen name text for the menu
    * @param string $capability The capability required for this menu to be displayed to the user
    * @param string $menuSlug The slug name to refer to this menu by (should be unique for this menu)
    * @param object $object The objected that will treat the request
    * @param string $method The callback method
    * @param string $iconUrl The icon for this menu
    * @param string $position The position in the menu order this menu should appear
    */
	public function addWPMenu(
        $pageTitle,
        $menuTitle,
        $capability,
        $menuSlug,
        $object,
        $method,
        $iconUrl='',
        $position=100
    ){
		add_menu_page(
            $pageTitle,
            $menuTitle,
            $capability,
			$menuSlug,
            array($object, $method),
            $iconUrl,
            $position
        );
	}

	/**
    * Add submenus from amenu to wp admin page
    *
    * @param string $parentSlug The slug name for the parent menu
    * @param string $pageTitle The text to be displayed in the title tags of the page when the menu is selected
    * @param string $menuTitle The text to be used for the menu
    * @param string $capability The capability required for this menu to be displayed to the user
    * @param string $menuSlug The slug name to refer to this menu by
    * @param object $object The objected that will treat the request
    * @param string $method The callback method
    */
	public function addWPSubMenu(
        $parentSlug,
        $pageTitle,
        $menuTitle,
        $capability,
        $menuSlug,
        $object,
        $method
    ){
		add_submenu_page(
            $parentSlug,
            $pageTitle,
            $menuTitle,
			$capability,
            $menuSlug,
            array($object, $method)
        );
	}

}
