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
class Helpers {

    public function url($controller = null, $action = null, $param1 = null, $param2 = null){
        $urlString = HOST . FOLDER_APP;
    	if ($controller) {
    		$urlString .= "/" .$controller;
            if ($action) {
                $urlString .= "/" . $action;
                if ($param1) {
                    $urlString .= "/" . $param1;
                    if ($param2) {
                        $urlString .= "/" . $param2;
                    }
                }
            }
    	}
        return $urlString;
    }

    public function redirect($url = null) {
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . FOLDER_APP . $url;
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }

    public function urlBase() {
        return 'http://' . $_SERVER['HTTP_HOST'] . FOLDER_APP ;

    }
}
?>
