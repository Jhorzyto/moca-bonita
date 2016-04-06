<?php
namespace SPS\controller;
use MocaBonita\controller\Controller;

class SPSController implements Controller {

	private $view;

	public function __construct(){
	}

	public function getRequest(array $get) {
		return ['teste' => 'tease'];
	}

	public function postRequest(array $post) {
		return ['teste' => 'tease'];
	}

	public function putRequest(array $put) {

	}

	public function deleteRequest(array $delete) {

	}
}