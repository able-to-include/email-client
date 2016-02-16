<?php
/**
 * Apache License, Version 2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * The work represented by this file is partially funded by the ABLE-TO-INCLUDE
 * project through the European Commission's ICT Policy Support Programme as
 * part of the Competitiveness & Innovation Programme (Grant no.: 621055)
 * Copyright © 2016, ABLE-TO-INCLUDE Consortium.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions & limitations
 * under the License.
 */
class FrontController {
	static function main() {
		session_start();
		if(! empty($_GET['code'])){
			$controllerName = 'accountController';
			$actionName = 'callback_google';
		} else {
			if (isset($_SESSION['client'])) {
				$controllerName = 'mailsController';
				$actionName = 'getAll';
			} else {
				$controllerName = 'accountController';
				$actionName = 'index';
			}
			if(isset($_GET['url'])) {
				$params = explode('/', $_GET['url']);
				if(isset($params[0])) {
					$controllerName = $params[0] . 'Controller';
					if(isset($params[1])) {
						$actionName = $params[1];
						if(isset($params[2])) {
							$paramName = $params[2];
							if(isset($params[3])) {
								$paramName2 = $params[3];
							}
						}
					}
				}
			}
		}
		//Incluimos algunas clases:
		require dirname(__FILE__) . '/../core/config.class.php'; //de configuracion
		require dirname(__FILE__) . '/../config/constants.php'; //Archivo con configuraciones.
		require dirname(__FILE__) . '/../core/view.class.php'; //Mini motor de plantillas
		$controllerPath = dirname(__FILE__) . '/'. $controllerName . '.php';
		//Incluimos el fichero que contiene nuestra clase controller solicitada
		if(is_file($controllerPath))
			require $controllerPath;
		else
			die('El controller ' . $controllerPath . ' no existe - 404 not found');
		//Si no existe la clase que buscamos y su acción, mostramos un error 404
		if (is_callable(array($controllerName, $actionName)) == false) {
			trigger_error ($controllerName . '->' . $actionName . '` no existe', E_USER_NOTICE);
			return false;
		}
		//Si todo esta bien, creamos una instancia del controller y llamamos a la acción
		$controller = new $controllerName();
		isset($paramName)
		?
		(isset($paramName2)
			?
			$controller->$actionName($paramName, $paramName2)
			:
			$controller->$actionName($paramName))
		:
		$controller->$actionName();
	}
}
?>
