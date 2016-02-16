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
require dirname(__FILE__). '/../libraries/google-api-php-client/src/Google/autoload.php';

abstract class GoogleHelper {
    public static function getClient() {
       // $config = self::loadConfig();
        $client = new Google_Client();

        $client->setAuthConfigFile(dirname(__FILE__).'/client_secret.json');
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . FOLDER_APP);
        $client->addScope(Google_Service_Gmail::MAIL_GOOGLE_COM);
        $client->addScope(Google_Service_Gmail::GMAIL_COMPOSE);
        $client->addScope(Google_Service_Gmail::GMAIL_MODIFY);
        $client->addScope('http://www.google.com/m8/feeds/');
        $client->addScope('https://www.googleapis.com/auth/userinfo.email');
        $client->setIncludeGrantedScopes(true);

        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        return $client;
    }
    public static function getAuthUrl(Google_Client $client) {
        return $client->createAuthUrl();
    }
    public static function authenticate(Google_Client $client, $code) {
        $client->authenticate($code);
    }
    public static function getAccessToken(Google_Client $client) {
        return json_decode($client->getAccessToken());
    }

    public static function refreshToken(Google_Client $client, $accessToken) {
        $client->refreshToken($accessToken->refresh_token);
        return $client;
    }
}
?>
