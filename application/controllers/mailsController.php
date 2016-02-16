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
require dirname(__FILE__). '/../models/MailsModel.php';
require dirname(__FILE__). '/../core/helpers.class.php';

/**
 * Emails controller.
 */
class mailsController {
    function __construct() {
        $this->view = new View();
        $this->helper=new Helpers();
        $this->meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre');
        $this->months = array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December');

    }

    public function getAll($mode = 'INBOX', $page = 0) {
        if(isset($_SESSION['client'])){

            if($page == 0){
                unset($_SESSION['pages']);
            }

            $typeMails['INBOX']['tag'] = 'From';
            $typeMails['INBOX']['title'] = 'RECEIVED';

            $typeMails['SENT']['tag'] = 'To';
            $typeMails['SENT']['title'] = 'SENT';

            $opt_param = array();
            $opt_param['labelIds'] =  $mode;
            $opt_param['maxResults'] = 5;

            $mails = array();

            //Creamos una instancia de nuestro "modelo"
            $mailsModel = new MailsModel();
            $data['NextPage'] = false;

            $pageToken = null;

            if(isset($_SESSION['pages'])){
                if(isset($_SESSION['pages'][$page-1])){
                    $pageToken = $_SESSION['pages'][$page-1];
                }
            } else {
                $_SESSION['pages'] = array();
            }

            $allMessages = $mailsModel->listMessages($opt_param, $pageToken);
            if ($pageToken) {
                array_push($_SESSION['pages'], $pageToken);
                $data['NextPage'] = true;
            }
            $data['ActualPage'] = $page;

            foreach ($allMessages as $message) {
                $mail = array();

                $mailDetail = $mailsModel->getMessage($message->getId());

                foreach ($mailDetail->getPayload()->getHeaders() as $header) {
                    $name = $header->getName();
                    $value = $header->getValue();
                    if($name === 'Date' || $name === 'Subject' || $name ===  $typeMails[$mode]['tag']){
                            $mail[$name] = $value;
                    }
                }

                //si es nuevo
                if(in_array('UNREAD', $mailDetail->getLabelIds())){
                    $mail['UNREAD'] = true;
                }

                //si tiene adjuntos
                if($mailsModel->containAttachments($message->getId())){
                    $mail['attachments'] = true;
                }

                //quita el correo
                $mail[$typeMails[$mode]['tag']] = preg_replace('/\\ <.*?\\>/','',$mail[$typeMails[$mode]['tag']]);
                $mail[$typeMails[$mode]['tag']] = str_replace('"','',$mail[$typeMails[$mode]['tag']]);

                //formateo de fecha para sacar la local (la que se muestra y la que se pasa al audio por que no interpreta la fecha y lee las /)
                $fecha = new DateTime($mail['Date']);
                $fecha->setTimezone(new DateTimeZone('Europe/Madrid'));


                $mail['Date'] = $fecha->format('d/m/Y, H:i');
                //$mail['DateAudio'] = $fecha->format('j \d\e F \d\e Y, \a \l\a\s H:i \h\o\r\a\s');
                $dia=$fecha->format('j');
                $mes=$fecha->format('m');
                $ano=$fecha->format('Y');
                $hora=$fecha->format('H');
                $minuto=$fecha->format('i');

                $mail['DateAudioES']= $dia.' de '.$this->meses[$mes].' de '.$ano.', a las '.$hora.' horas y '.$minuto.' minutos.';
                $mail['DateAudioEN']= $dia . $this->getOrdinal($dia) . $this->months[$mes].' '.$ano.', at '.$hora.':'.$minuto.'.';

                $mails[$message->getId()] = $mail;
            }

            //sacamos el número de mensajes sin leer
            $data['Unreads'] = $mailsModel->countMessages(array('labelIds' => 'UNREAD'));

            //Pasamos a la vista toda la información que se desea representar
            $data['Mails'] = $mails;
            $data['Title'] = $typeMails[$mode]['title'];
            $data[$mode] = true;

            //Finalmente presentamos nuestra plantilla
            $this->view->show('mailsView.php', $data);
        } else {
            $this->helper->redirect();
        }
    }

    public function get($mode = 'INBOX', $idMail) {
        if(isset($_SESSION['client'])){

            $typeMails['INBOX']['tag'] = 'From';
            $typeMails['INBOX']['title'] = 'RECEIVED_EMAIL';

            $typeMails['SENT']['tag'] = 'To';
            $typeMails['SENT']['title'] = 'SENT_EMAIL';

            //Creamos una instancia de nuestro "modelo"
            $mailsModel = new MailsModel();
            $mail = array();
            $message = $mailsModel->getMessage($idMail);

            $mail['Id'] = $idMail;

            //var_dump($message->getPayload()->getHeaders());

            foreach ($message->getPayload()->getHeaders() as $header) {
                $name = $header->getName();
                $value = $header->getValue();
                //if($name === 'Date' || $name === 'Subject' || $name === $typeMails[$mode]['tag'] ){
                if($name === 'Date' || $name === 'Subject' || $name === 'From' || $name === 'To' || $name === 'Cc' || $name === 'Bcc'  || $name === 'Reply-To'){
                        $mail[$name] = $value;
                }
            }

            //si es nuevo
            if(in_array('UNREAD', $message->getLabelIds())) {
                $result = $mailsModel->setRead($idMail);
            }

            //formateo de fecha para sacar la local (la que se muestra y la que se pasa al audio por que no interpreta la fecha y lee las /)
            $fecha = new DateTime($mail['Date']);
            $fecha->setTimezone(new DateTimeZone('Europe/Madrid'));
            $mail['Date'] = $fecha->format('d/m/Y \a \l\a\s H:i');

            $body = array();
            $util = new Google_Utils();

           // $content = $util::urlSafeB64Decode($message->getRaw());

            $this->getBodyRecursive($body, $message->getPayload());

            //si tiene adjuntos
            if($mailsModel->containAttachments($message->getId())){
                $mail['attachments'] = true;

                //saca adjuntos
                $attachments = $mailsModel->getAttachments($idMail);
                $mail['attachmentsArray'] = $attachments;
            }

            //sacamos el número de mensajes sin leer
            $data['Unreads'] = $mailsModel->countMessages(array('labelIds' => 'UNREAD'));

            $mail['Body'] = $body;

            $data['Mail'] = $mail;
            $data['Title'] = $typeMails[$mode]['title'];
            $data[$mode] = true;
            //Finalmente presentamos nuestra plantilla
            $this->view->show('mailView.php', $data);
        } else {
            $this->helper->redirect();
        }
    }

    private function getBodyRecursive(& $body = null, $payload) {
        if($payload->getParts())  {
            foreach ($payload->getParts() as $part) {
                $this->getBodyRecursive($body, $part);
            }
        }else{

            if($payload->getBody()) {
                $aux = array();
                $aux['mime'] = $payload->getMimeType();
                $aux['content'] = null;
                if($payload->getBody()->getData()){
                    $util = new Google_Utils();
                    $aux['content'] = $util::urlSafeB64Decode($payload->getBody()->getData());
                    if(strcmp($aux['mime'], 'text/plain') == 0){
                        //quita las etiquetas html
                        $aux['content'] = preg_replace('/\\<(?i).*?>/','',$aux['content']);
                        $aux['content'] = str_replace("<'|'>", '', $aux['content']);

                        //convierte los saltos de linea en br
                        $aux['content'] = str_replace(PHP_EOL, '<br/>', $aux['content']);

                        //quita las etiquetas de imagenes (está entre corchetes)
                        $aux['content'] = preg_replace('/\\[.*?\\]/','',$aux['content']);
                    }

                }
                array_push($body, $aux);
            }
        }
    }

    public function delete($idMail, $mode = 'INBOX') {
        if(isset($_SESSION['client'])){

            //Creamos una instancia de nuestro "modelo"
            $mailsModel = new MailsModel();

            $aux= $mailsModel->delete($idMail);

            //echo $aux;

            $this->helper->redirect('/mails/getAll/'.$mode);
        } else {
            $this->helper->redirect();
        }
    }

    public function newMail($mode = null) {
        if(isset($_SESSION['client'])){
            if($mode)   {
                //Pasamos a la vista toda la información que se desea representar
                $data['Title'] = 'REPLY';
                $data['RE'] = true;
                $mail = array();
                $mail['to'] = $_POST['to'];

                $mail['subject'] = $_POST['subject'];
                $mail['body'] = str_replace('<br/>', PHP_EOL, $_POST['body']);
                $data['Mail'] = $mail;

            }else{
                //Pasamos a la vista toda la información que se desea representar
                $data['Title'] = 'NEW_EMAIL';
                $data['NEW'] = true;
            }

            //sacamos el número de mensajes sin leer
            $mailsModel = new MailsModel();
            $data['Unreads'] = $mailsModel->countMessages(array('labelIds' => 'UNREAD'));

            //Finalmente presentamos nuestra plantilla
            $this->view->show('sendView.php', $data);

        } else {
            $this->helper->redirect();
        }
    }

    public function send() {
        if(isset($_SESSION['client'])){

            $util = new Google_Utils();

            require_once dirname(__FILE__). '/../libraries/PHPMailer/class.phpmailer.php';
            $mail = new PHPMailer();
            $mail->ContentType = 'text/plain';
            $mail->CharSet = "UTF-8";
            $subject = $_POST['subject'];
            $msg = $_POST['body'];
            $from = $_SESSION['user']->email;
            $fname = ($_SESSION['user']->name) ? $_SESSION['user']->name : $_SESSION['user']->email;

            $mail->From = $from;
            $mail->FromName = $fname;
            $recipients = split(',', $_POST['mail']);
            foreach ($recipients as $value) {
                $mail->AddAddress($value);
            }
            $mail->AddReplyTo($from,$fname);
            $mail->Subject = $subject;
            $mail->Body    = $msg;

            $validAttachments = array();
            foreach($_FILES['attachments']['name'] as $index => $fileName) {
                $filePath = $_FILES['attachments']['tmp_name'][$index];
                if(is_uploaded_file($filePath)) {
                    $attachment = new stdClass;
                    $attachment->fileName = $fileName;
                    $attachment->filePath = $filePath;
                    $validAttachments[] = $attachment;
                }
            }
            foreach($validAttachments as $attachment) {
                $mail->AddAttachment($attachment->filePath, $attachment->fileName);
            }
            $mail->preSend();
            $mime = $mail->getSentMIMEMessage();

            $message = new Google_Service_Gmail_Message();
            $raw = $util::urlSafeB64Encode($mime);
            $message->setRaw($raw);

            //Creamos una instancia de nuestro "modelo"
            $mailsModel = new MailsModel();

            $aux = $mailsModel->sendMessage($message);

            $this->helper->redirect('/mails/getAll/SENT');

        } else {
            $this->helper->redirect();
        }
    }

    /**
     * Returns the ending of the ordinal number to be added to the days on the
     * dates.
     */
    public function getOrdinal($dia) {
      switch($dia) {
        case 1:
        case 21:
        case 31:
          return 'st ';
        case 2:
        case 22:
          return 'nd ';
        case 3:
        case 23:
          return 'rd ';
        default:
          return 'th ';
      }
    }
}

?>
