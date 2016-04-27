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
     * Array with the pages to remove submenu
     *
     * @var array
     */
    private $wpRemoveSubenu;

	/**
    * Class constructor
    *
    */
	public function __construct(){
		$this->wpMenuItems      = [];
		$this->wpMenuSubItems   = [];
        $this->wpPagesAvailable = [];
        $this->wpRemoveSubenu   = [];
	}

	/**
    * Set menu items
    *
    * @param array $wpMenuItems An array with items
    */
	public function setMenuItems(array $wpMenuItems){
		$this->wpMenuItems[]      = $wpMenuItems;
        $this->wpPagesAvailable[] = $wpMenuItems['menu_slug'];

        if($wpMenuItems['remove_submenu'])
            $this->wpRemoveSubenu[] = $wpMenuItems['menu_slug'];
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
		$this->wpMenuSubItems[]   = $wpMenuSubItems;
        $this->wpPagesAvailable[] = $wpMenuSubItems['menu_slug'];
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
			add_menu_page(
					$wpMenuItem['menu_title'],
					$wpMenuItem['menu_title'],
					$wpMenuItem['capability'],
					$wpMenuItem['menu_slug'],
					[$wpMenuItem['object'], $wpMenuItem['method']],
					$wpMenuItem['icon'],
					$wpMenuItem['position']
			);
	}

	/**
    * Add a set of submenus from a menu to wp admin page
    *
    */
	public function addSubMenu(){
        if(count($this->wpMenuSubItems)){
            foreach($this->wpMenuSubItems as &$wpMenuSubItem)
                add_submenu_page(
                    $wpMenuSubItem['parent_slug'],
                    $wpMenuSubItem['menu_title'],
                    $wpMenuSubItem['menu_title'],
                    $wpMenuSubItem['capability'],
                    $wpMenuSubItem['menu_slug'],
                    [$wpMenuSubItem['object'], $wpMenuSubItem['method']]
                );

            foreach($this->wpRemoveSubenu as &$wpRemoveSubenu)
                remove_submenu_page($wpRemoveSubenu, $wpRemoveSubenu);
        }
	}

}
