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
require dirname(__FILE__). '/../core/Contact.class.php';
class ContactsModel {

    public static function getContact(){
        $client = unserialize($_SESSION['client']);
        $req = new Google_Http_Request('https://www.googleapis.com/oauth2/v2/userinfo');
        $val = $client->getAuth()->authenticatedRequest($req);
        $response = $val->getResponseBody();
        $user = json_decode($response);

        return($user);
    }

    public static function listContacts(){
        $client = unserialize($_SESSION['client']);
        $req = new Google_Http_Request('https://www.google.com/m8/feeds/contacts/default/full?max-results=10000&updated-min=2007-03-16T00:00:00');
        $val = $client->getAuth()->authenticatedRequest($req);
        $response = $val->getResponseBody();
        $xmlContacts = simplexml_load_string($response);
        $xmlContacts->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
        foreach ($xmlContacts->entry as $xmlContactsEntry) {
            $contactDetails = array();
            $contactDetails['id'] = (string) $xmlContactsEntry->id;
            $contactDetails['name'] = (string) $xmlContactsEntry->title;
            foreach ($xmlContactsEntry->children() as $key => $value) {
                $attributes = $value->attributes();
                if ($key == 'link') {
                    if ($attributes['rel'] == 'edit') {
                        $contactDetails['editURL'] = (string) $attributes['href'];
                    } elseif ($attributes['rel'] == 'self') {
                        $contactDetails['selfURL'] = (string) $attributes['href'];
                    }
                }
            }
            $contactGDNodes = $xmlContactsEntry->children('http://schemas.google.com/g/2005');
            foreach ($contactGDNodes as $key => $value) {
                $attributes = $value->attributes();
                if ($key == 'email') {
                    $contactDetails[$key] = (string) $attributes['address'];
                } else {
                    $contactDetails[$key] = (string) $value;
                }
            }
            $contactsArray[] = new Contact($contactDetails);
        }
        return $contactsArray;
    }

    public static function getBySelfURL($selfURL) {
        //$client = GoogleHelper::getClient();
        $client = unserialize($_SESSION['client']);
        $req = new Google_Http_Request($selfURL);
        $val = $client->getAuth()->authenticatedRequest($req);
        $response = $val->getResponseBody();
        $xmlContact = simplexml_load_string($response);
        $xmlContact->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
        $xmlContactsEntry = $xmlContact;
        $contactDetails = array();
        $contactDetails['id'] = (string) $xmlContactsEntry->id;
        $contactDetails['name'] = (string) $xmlContactsEntry->title;
        foreach ($xmlContactsEntry->children() as $key => $value) {
            $attributes = $value->attributes();
            if ($key == 'link') {
                if ($attributes['rel'] == 'edit') {
                    $contactDetails['editURL'] = (string) $attributes['href'];
                } elseif ($attributes['rel'] == 'self') {
                    $contactDetails['selfURL'] = (string) $attributes['href'];
                }
            }
        }
        $contactGDNodes = $xmlContactsEntry->children('http://schemas.google.com/g/2005');
        foreach ($contactGDNodes as $key => $value) {
            $attributes = $value->attributes();
            if ($key == 'email') {
                $contactDetails[$key] = (string) $attributes['address'];
            } else {
                $contactDetails[$key] = (string) $value;
            }
        }
        return new Contact($contactDetails);
    }
    public static function submitUpdates(Contact $updatedContact) {
        //$client = GoogleHelper::getClient();
        $client = unserialize($_SESSION['client']);
        $req = new Google_Http_Request($updatedContact->selfURL);
        $val = $client->getAuth()->authenticatedRequest($req);
        $response = $val->getResponseBody();
        $xmlContact = simplexml_load_string($response);
        $xmlContact->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
        $xmlContactsEntry = $xmlContact;
        $xmlContactsEntry->title = $updatedContact->name;
        $contactGDNodes = $xmlContactsEntry->children('http://schemas.google.com/g/2005');
        foreach ($contactGDNodes as $key => $value) {
            $attributes = $value->attributes();
            if ($key == 'email') {
                $attributes['address'] = $updatedContact->email;
            } else {
                $xmlContactsEntry->$key = $updatedContact->$key;
                $attributes['uri'] = '';
            }
        }
        $updatedXML = $xmlContactsEntry->asXML();
        $req = new Google_Http_Request($updatedContact->editURL);
        $req->setRequestHeaders(array('content-type' => 'application/atom+xml; charset=UTF-8; type=feed'));
        $req->setRequestMethod('PUT');
        $req->setPostBody($updatedXML);
        $val = $client->getAuth()->authenticatedRequest($req);
        $response = $val->getResponseBody();
        $xmlContact = simplexml_load_string($response);
        $xmlContact->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
        $xmlContactsEntry = $xmlContact;
        $contactDetails = array();
        $contactDetails['id'] = (string) $xmlContactsEntry->id;
        $contactDetails['name'] = (string) $xmlContactsEntry->title;
        foreach ($xmlContactsEntry->children() as $key => $value) {
            $attributes = $value->attributes();
            if ($key == 'link') {
                if ($attributes['rel'] == 'edit') {
                    $contactDetails['editURL'] = (string) $attributes['href'];
                } elseif ($attributes['rel'] == 'self') {
                    $contactDetails['selfURL'] = (string) $attributes['href'];
                }
            }
        }
        $contactGDNodes = $xmlContactsEntry->children('http://schemas.google.com/g/2005');
        foreach ($contactGDNodes as $key => $value) {
            $attributes = $value->attributes();
            if ($key == 'email') {
                $contactDetails[$key] = (string) $attributes['address'];
            } else {
                $contactDetails[$key] = (string) $value;
            }
        }
        return new Contact($contactDetails);
    }

    public static function delete(Contact $updatedContact) {
        //$client = GoogleHelper::getClient();
        $client = unserialize($_SESSION['client']);
        $req = new Google_Http_Request($updatedContact->selfURL);
        $val = $client->getAuth()->authenticatedRequest($req);
        $response = $val->getResponseBody();

        $req = new Google_Http_Request($updatedContact->selfURL);
        $req->setRequestHeaders(array('X-HTTP-Method-Override' => 'DELETE', 'GData-Version' => '3.0','If-Match' => '*'));
        $req->setRequestMethod('POST');
        $val = $client->getAuth()->authenticatedRequest($req);
        $response = $val->getResponseBody();

        return new Contact($response);
    }
    public static function create($name, $emailAddress) {
        $doc = new DOMDocument();
        $doc->formatOutput = true;
        $entry = $doc->createElement('atom:entry');
        $entry->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:atom', 'http://www.w3.org/2005/Atom');
        $entry->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:gd', 'http://schemas.google.com/g/2005');
        $entry->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:gd', 'http://schemas.google.com/g/2005');
        $doc->appendChild($entry);

        $title = $doc->createElement('title', $name);
        $entry->appendChild($title);

        $email = $doc->createElement('gd:email');
        $email->setAttribute('rel', 'http://schemas.google.com/g/2005#work');
        $email->setAttribute('address', $emailAddress);
        $entry->appendChild($email);

        $xmlToSend = $doc->saveXML();

        //$client = GoogleHelper::getClient();
        $client = unserialize($_SESSION['client']);
        $req = new Google_Http_Request('https://www.google.com/m8/feeds/contacts/default/full');
        $req->setRequestHeaders(array('content-type' => 'application/atom+xml; charset=UTF-8; type=feed'));
        $req->setRequestMethod('POST');
        $req->setPostBody($xmlToSend);
        $val = $client->getAuth()->authenticatedRequest($req);
        $response = $val->getResponseBody();
        $xmlContact = simplexml_load_string($response);
        $xmlContact->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
        $xmlContactsEntry = $xmlContact;
        $contactDetails = array();
        $contactDetails['id'] = (string) $xmlContactsEntry->id;
        $contactDetails['name'] = (string) $xmlContactsEntry->title;
        foreach ($xmlContactsEntry->children() as $key => $value) {
            $attributes = $value->attributes();
            if ($key == 'link') {
                if ($attributes['rel'] == 'edit') {
                    $contactDetails['editURL'] = (string) $attributes['href'];
                } elseif ($attributes['rel'] == 'self') {
                    $contactDetails['selfURL'] = (string) $attributes['href'];
                }
            }
        }
        $contactGDNodes = $xmlContactsEntry->children('http://schemas.google.com/g/2005');
        foreach ($contactGDNodes as $key => $value) {
            $attributes = $value->attributes();
            if ($key == 'email') {
                $contactDetails[$key] = (string) $attributes['address'];
            } else {
                $contactDetails[$key] = (string) $value;
            }
        }
        return new Contact($contactDetails);
    }
}
?>
