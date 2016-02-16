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
require dirname(__FILE__). '/../models/ContactsModel.php';
require_once dirname(__FILE__). '/../core/helpers.class.php';


class contactsController {
    function __construct() {
        $this->view = new View();
        $this->config = Config::singleton();
        $this->helper=new Helpers();
    }

    public function getUser() {
        if(isset($_SESSION['client'])){
            $contactsModel = new ContactsModel();
            $contact = $contactsModel->getContact();
            $_SESSION['user'] = $contact;
        }
    }

    public function getAll() {
        if(isset($_SESSION['client'])){
            require dirname(__FILE__). '/../models/MailsModel.php';
            $client = unserialize($_SESSION['client']);

           /* var_dump($_SESSION['access_token']);
            if($client->isAccessTokenExpired()){
                var_dump($client->isAccessTokenExpired());
                $refresh=$client->getRefreshToken();
                var_dump($refresh);
            }*/

            $mailsModel = new MailsModel();
            //sacamos el número de mensajes sin leer
            $data['Unreads'] = $mailsModel->countMessages(array('labelIds' => 'UNREAD'));


            $contactsModel = new ContactsModel();
            $contacts = $contactsModel->listContacts();

            //Pasamos a la vista toda la información que se desea representar
            $data['Contacts'] = $contacts;
            $data['Title'] = 'CONTACTS';
            $data['CONTACTS'] = true;

            //Finalmente presentamos nuestra plantilla
            $this->view->show('contactsView.php', $data);
        } else {
            $this->helper->redirect();
        }
    }

    public function updateOrDelete() {
        if(isset($_SESSION['client'])){
            $client = unserialize($_SESSION['client']);

            if (isset($_POST['update'])) {
                $contactsModel = new ContactsModel();
                $contact = $contactsModel->getBySelfURL($_POST['selfURL']);
                $contact->name = $_POST['name'];
                $contact->email = $_POST['mail'];
                $contactAfterUpdate = $contactsModel->submitUpdates($contact);
            } else {
                if (isset($_POST['delete'])) {
                    $contactsModel = new ContactsModel();
                    $contact = $contactsModel->getBySelfURL($_POST['selfURL']);
                    //var_dump($contact);
                    //$contact->name = $_POST['name'];
                    //$contact->email = $_POST['mail'];
                    $contactAfterUpdate = $contactsModel->delete($contact);
                }
            }
            $this->helper->redirect('/contacts/getAll');
        } else {
            $this->helper->redirect();
        }
    }

    public function add() {
        if(isset($_SESSION['client'])){
            $client = unserialize($_SESSION['client']);

            if (isset($_POST['add'])) {
                $contactsModel = new ContactsModel();
                $contact = $contactsModel->create($_POST['name'], $_POST['mail']);
            }
            $this->helper->redirect('/contacts/getAll');
        } else {
            $this->helper->redirect();
        }
    }

}

?>
