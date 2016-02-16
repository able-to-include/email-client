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
class MailsModel {

  /**
   * Get the attachments in a given email.
   *
   * @param messageId ID of Message containing attachment..
   * @throws IOException
   */
  function getAttachments($messageId){
    try {
      $client = unserialize($_SESSION['client']);
      $service  = new Google_Service_Gmail($client);
      $user = 'me';
      $util = new Google_Utils();

      $message = $service->users_messages->get($user, $messageId);
      $parts = $message->getPayload()->getParts();
      $body = array();

      foreach ($parts as $part) {
        if ($part->getFilename() != null && strlen($part->getFilename()) > 0) {

          $filename = $part->getFilename();
          $attId = $part->getBody()->getAttachmentId();
          $body[$filename] = array();

          $attachPart = $service->users_messages_attachments->get($user, $messageId, $attId);
          $body[$filename]['content'] = base64_encode($util::urlSafeB64Decode($attachPart->getData()));
          $ext = pathinfo($filename, PATHINFO_EXTENSION);
          switch ($ext) {
            case 'docx':
            case 'doc':
            case 'txt':
            case 'pdf':
              $ext = 'text';
              break;
            case 'jpg':
            case 'png':
            case 'gif':
            case 'jpeg':
              $ext = 'image';
              break;
            default:
              $ext = 'file';
              break;
          }
          $body[$filename]['ext'] = $ext;
        }
      }
      return $body;
    } catch (Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
    }
  }

  /**
   * Download the attachments in a given email.
   *
   * @param messageId ID of Message containing attachment..
   * @throws IOException
   */
  function downloadAttachments($messageId){
    try {
      $client = unserialize($_SESSION['client']);
      $service  = new Google_Service_Gmail($client);
      $user = 'me';
      $util = new Google_Utils();

      $message = $service->users_messages->get($user, $messageId);
      $parts = $message->getPayload()->getParts();
      $body = array();

      foreach ($parts as $part) {
        if ($part->getFilename() != null && strlen($part->getFilename()) > 0) {

          $filename = $part->getFilename();
          $attId = $part->getBody()->getAttachmentId();

          $attachPart = $service->users_messages_attachments->get($user, $messageId, $attId);
          array_push($body, $util::urlSafeB64Decode($attachPart->getData()));
        }
      }
      return $body;
    } catch (Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
    }
  }

  /**
   * Contains attachments in a given email.
   *
   * @param messageId ID of Message containing attachment..
   * @throws IOException
   */
  function containAttachments($messageId){
    try {
      $client = unserialize($_SESSION['client']);
      $service  = new Google_Service_Gmail($client);
      $user = 'me';
      $util = new Google_Utils();

      $message = $service->users_messages->get($user, $messageId);
      $parts = $message->getPayload()->getParts();
      $attId = null;

      foreach ($parts as $part) {
        if ($part->getFilename() != null && strlen($part->getFilename()) > 0) {
          $attId = $part->getBody()->getAttachmentId();
          if($attId){
            return true;
          }
        }
      }
      return false;
    } catch (Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
    }
  }

    /**
     * Get Message with given ID.
     *
     * @param  Google_Service_Gmail $service Authorized Gmail API instance.
     * @param  string $messageId ID of Message to get.
     * @return Google_Service_Gmail_Message Message retrieved.
     */
    function getMessage($messageId) {
      try {
        $client = unserialize($_SESSION['client']);
        $service  = new Google_Service_Gmail($client);
        $user = 'me';
        $opt_param = array();
       // $opt_param['format'] =  'RAW';
        $message = $service->users_messages->get($user, $messageId, $opt_param);
        //print 'Message with ID: ' . $message->getId() . ' retrieved.';
        return $message;
      } catch (Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
      }
    }

    /**
     * Get list of Messages in user's mailbox.
     *
     * @param  Google_Service_Gmail $service Authorized Gmail API instance.
     * @param  string $userId User's email address. The special value 'me'
     * can be used to indicate the authenticated user.
     * @return array Array of Messages.
     */

    function listMessages($opt_param, &$pageToken) {
      $client = unserialize($_SESSION['client']);
      $service  = new Google_Service_Gmail($client);
      $user = 'me';
      $messages = array();
      try {
        if ($pageToken) {
          $opt_param['pageToken'] = $pageToken;
        }
        $messagesResponse = $service->users_messages->listUsersMessages($user, $opt_param);
        if ($messagesResponse->getMessages()) {
          //array_push($messages, $messagesResponse->getMessages());
          $messages = array_merge($messages, $messagesResponse->getMessages());
          $pageToken = $messagesResponse->getNextPageToken();
        }
      } catch (Exception $e) {
       return 'An error occurred: ' . $e->getMessage();
      }
      return $messages;
    }

    /**
     * Count Messages in user's mailbox.
     *
     * @param  Google_Service_Gmail $service Authorized Gmail API instance.
     * @param  string $userId User's email address. The special value 'me'
     * can be used to indicate the authenticated user.
     * @return array Array of Messages.
     */

    function countMessages($opt_param) {
      $client = unserialize($_SESSION['client']);
      $service  = new Google_Service_Gmail($client);
      $user = 'me';
      $messages =0;

      try {
        $messagesResponse = $service->users_messages->listUsersMessages($user, $opt_param);
        if ($messagesResponse->getResultSizeEstimate()) {
          $messages =  $messagesResponse->getResultSizeEstimate();
        }
      } catch (Exception $e) {
       return 'An error occurred: ' . $e->getMessage();
      }
      return $messages;
    }

    /**
     * Send Message.
     *
     * @param  Google_Service_Gmail $service Authorized Gmail API instance.
     * @param  string $userId User's email address. The special value 'me'
     * can be used to indicate the authenticated user.
     * @param  Google_Service_Gmail_Message $message Message to send.
     * @return Google_Service_Gmail_Message sent Message.
     */
    function sendMessage($message) {
      try {
        $client = unserialize($_SESSION['client']);
        $service  = new Google_Service_Gmail($client);
        $user = 'me';

        $message = $service->users_messages->send($user, $message);
        return $message;
      } catch (Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
      }
    }


    public function delete($mailId){
      try {
        $client = unserialize($_SESSION['client']);
        $service  = new Google_Service_Gmail($client);
        $user = 'me';
        $mail = $service->users_messages->trash($user, $mailId);

        return $mail;
      }catch(Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
      }
    }

    public function setRead($messageId){
      try {
        var_dump($labels);
        $client = unserialize($_SESSION['client']);
        $service  = new Google_Service_Gmail($client);
        $user = 'me';
        $postBody = new Google_Service_Gmail_ModifyMessageRequest();
        $array = array();
        array_push($array, 'UNREAD');
        $postBody->setRemoveLabelIds($array);
        $mail = $service->users_messages->modify($user, $messageId, $postBody);
      }catch(Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
      }
    }
}
?>
