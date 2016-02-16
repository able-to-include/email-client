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
session_start();
header('Cache-control: private'); // IE 6 FIX

if(isSet($_GET['lang'])) {
  $lang = $_GET['lang'];
  // register the session and set the cookie
  $_SESSION['lang'] = $lang;
  setcookie('kolumbaLang', $lang, time() + (3600 * 24 * 30),"/", ".kolumba.eu");
} else if(isSet($_COOKIE['kolumbaLang'])) {
  $lang = $_COOKIE['kolumbaLang'];
} else {
  //Check the browser language and sets it
  $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  switch ($browser_lang){
    case "es":
        $lang = 'es';
        break;
    default:
        $lang = 'en';// if the language is not supported, use English
        break;
  }
}

switch ($lang) {
  case 'en':
  $lang_file = 'lang.en.php';
  break;

  case 'es':
  $lang_file = 'lang.es.php';
  break;

  default:
  $lang_file = 'lang.en.php';
}

include_once 'languages/'.$lang_file;
?>
