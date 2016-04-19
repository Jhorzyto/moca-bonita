<?php

namespace MocaBonita\includes\json;

use MocaBonita\controller\HTTPService;

/**
* Treats JSON requests.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\json
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

class JSONService {

	/**
    * Send the JSON or TEXT response
    *
    * @param array $msg The response message
    * @return Message to be sent
    */
	static public function sendJSON(array $msg, HTTPService $HTTPService){
		$responseSuccess = function($code) use (&$msg){
			return [
					'meta' => ['code' => $code],
					'data' => $msg,
			];
		};

		$responseError = function() use (&$msg){
			return [
					'meta' => [
							'code'          => $msg['http_method']['code'],
							'error_message' => $msg['http_method']['error_message'],
					],
			];
		};

		if($HTTPService->isGET())
			wp_send_json(isset($msg['http_method']) ? $responseError() : $responseSuccess(200));

		elseif($HTTPService->isPOST() || $HTTPService->isPUT())
            wp_send_json(isset($msg['http_method']) ? $responseError() : $responseSuccess(201));

		elseif($HTTPService->isDELETE())
            wp_send_json(isset($msg['http_method']) ? $responseError() : $responseSuccess(204));

		else
            wp_send_json($msg);
	}

	/**
    * Decode the string from JSON format
    *
    * @param array $str The array to be decoded
    * @return Decoded array
    */
	public function decode($str){
		return json_decode($str, true);
	}

	/**
    * Encode the string from JSON format
    *
    * @param string $str The string to be encoded
    * @return Encoded array
    */
	public function encode($str){
		return json_encode($str);
	}

	/**
    * Check if a string is in JSON format
    *
    * @param string $str The string to be checked
    * @return True if it's JSON, false if it's not
    */
	public function isJSON($str){
		return is_string($str) && is_object(json_decode($str)) ? true : false;
	}
}
