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
require dirname(__FILE__) .'/../core/helpers.class.php';
require dirname(__FILE__). '/../core/GoogleHelper.php';

class accountController {

	function __construct() {
        $this->view = new View();
        //$this->config = Config::singleton();
        $this->helper=new Helpers();
    }

	public function index() {
        $data['title'] = 'ABLE MAIL';
        $data['login'] = true;

        $this->view->show('loginView.php', $data);
	}
   	public function login_google() {

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  	$this->helper->redirect('/mails/getAll');
		} else {
			$this->callback_google();
		}
    }

    public function callback_google(){
    	$client = GoogleHelper::getClient();

		if (!isset($_GET['code'])) {
		  $auth_url = GoogleHelper::getAuthUrl($client);
		  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
		} else {
			GoogleHelper::authenticate($client, $_GET['code']);
			unset($_SESSION['code']);
			//$client->authenticate($_GET['code']);
			$accessToken = GoogleHelper::getAccessToken($client);
			$_SESSION['access_token'] = $accessToken;
			if (isset($accessToken->refresh_token)) {
				$client = GoogleHelper::refreshToken($client, $accessToken);
			}
			$_SESSION['client'] = serialize($client);

			//carga el perfil del usuario identificado
			require dirname(__FILE__). '/contactsController.php';
			$controller = new contactsController();
			$controller->getUser();

		  	$this->login_google();
		}
	}

	public function logout() {
		unset($_SESSION['access_token']);
		unset($_SESSION['client']);
		unset($_SESSION['pages']);

		$this->index();
	}
}

?>
