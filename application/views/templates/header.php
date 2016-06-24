<!DOCTYPE html>
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
?>
<?php require_once("common.php"); ?>
<html lang="<?php echo $lang['SELECTED_LANGUAGE'];?>">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content=''>

    <link rel='icon' href='<?php echo $helper->urlBase(); ?>/public/favicon.ico'/>

    <title><?=$lang['PAGE_TITLE']?></title>

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#18BC9C">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#18BC9C">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#18BC9C">


    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href='<?php echo $helper->urlBase(); ?>/public/css/bootstrap.css' rel='stylesheet'>

    <!-- Custom CSS -->
    <link href='<?php echo $helper->urlBase(); ?>/public/css/freelancer.css' rel='stylesheet'>
    <link href='<?php echo $helper->urlBase(); ?>/public/css/style.css' rel='stylesheet'>


    <!-- Custom Fonts -->
    <link href='<?php echo $helper->urlBase(); ?>/public/font-awesome/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic' rel='stylesheet' type='text/css'>

    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

     <!-- jQuery -->
    <script src='<?php echo $helper->urlBase(); ?>/public/js/jquery.js'></script>

    <!-- Bootstrap Core JavaScript -->
    <script src='<?php echo $helper->urlBase(); ?>/public/js/bootstrap.min.js'></script>

    <!-- Plugin JavaScript -->
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>

    <!--<script src='<?php echo $helper->urlBase(); ?>/public/js/classie.js'></script>
    <script src='<?php echo $helper->urlBase(); ?>/public/js/cbpAnimatedHeader.js'></script>-->

    <!-- Contact Form JavaScript -->
    <script src='<?php echo $helper->urlBase(); ?>/public/js/jqBootstrapValidation.js'></script>
    <script src='<?php echo $helper->urlBase(); ?>/public/js/contact_me.js'></script>

    <!-- Custom Theme JavaScript -->
    <script src='<?php echo $helper->urlBase(); ?>/public/js/freelancer.js'></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
        <script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
    <![endif]-->

    <link href='<?php echo $helper->urlBase(); ?>/public/css/popup.css' rel='stylesheet' type='text/css' />

    <script type='text/javascript' src='<?php echo $helper->urlBase(); ?>/public/js/popup_delete.js'></script>
</head>

<body id='page-top' class='index'>
    <?php if (!isset($vars['login'])) { ?>
    <!-- Navigation -->
    <nav class='navbar navbar-default navbar-fixed-top'>

        <div class='container'>
          <a class='navbar-brand' href='<?php echo $helper->url('mails','getAll'); ?>'><?=$lang['PAGE_TITLE']?></a>
          <div class='user'>
            <a href="?lang=es"><?php echo $lang['ES'];?></a> | <a href="?lang=en"><?php echo $lang['EN'];?></a>
            <?=$lang['WELCOME']?>, <?php echo ($_SESSION['user']->name) ? $_SESSION['user']->name : $_SESSION['user']->email ?>
          </div>
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class='navbar-header page-scroll'>
                <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
                    <span class='sr-only'>Toggle navigation</span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </button>

            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
                <ul class='nav navbar-nav navbar-right'>
                    <li class='hidden'>
                        <a href='#page-top'></a>
                    </li>

                    <li class='page-scroll btn btn-xs btn-success <?php echo isset($vars['INBOX']) ? 'active' : ''; ?>'  style="margin: 2px;">
                        <a href='<?php echo $helper->url('mails','getAll'); ?>' style="background: none;">
                          <img class='icono' src='<?php echo $helper->urlBase(); ?>/public/img/icons/inbox.png' alt='<?=$lang['RECEIVED']?>'/> <?=$lang['RECEIVED']?> <?php echo isset($vars['Unreads']) && $vars['Unreads']>0 ? '('.$vars['Unreads'].')' : ''; ?>
                        </a>
                      </li>



                    <li class='page-scroll btn btn-xs btn-success <?php echo isset($vars['SENT']) ? 'active' : ''; ?>'  style="margin: 2px;">
                      <a href='<?php echo $helper->url('mails','getAll', 'SENT'); ?>' style="background: none;">
                        <img class='icono' src='<?php echo $helper->urlBase(); ?>/public/img/icons/sent.png' alt='<?=$lang['SENT']?>' /> <?=$lang['SENT']?>
                      </a>
                    </li>


                    <li class='page-scroll btn btn-xs btn-success <?php echo isset($vars['NEW']) ? 'active' : ''; ?>'  style="margin: 2px;">
                      <a href='<?php echo $helper->url('mails','newMail'); ?>' style="background: none;">
                         <img class='icono' src='<?php echo $helper->urlBase(); ?>/public/img/icons/new.png' alt='<?=$lang['NEW_EMAIL']?>'/> <?=$lang['NEW_EMAIL']?>
                      </a>
                    </li>

                    <li class='page-scroll btn btn-xs btn-success <?php echo isset($vars['CONTACTS']) ? 'active' : ''; ?>'  style="margin: 2px;">
                      <a href='<?php echo $helper->url('contacts','getAll'); ?>' style="background: none;">
                        <img class='icono' src='<?php echo $helper->urlBase(); ?>/public/img/icons/contacts.png' alt='<?=$lang['CONTACTS']?>' /> <?=$lang['CONTACTS']?>
                      </a>
                    </li>

                    <li class='page-scroll btn btn-xs btn-danger' style="margin: 2px;">
                      <a href='<?php echo $helper->url('account','logout'); ?>' style="background: none;">
                        <img class='icono' src='<?php echo $helper->urlBase(); ?>/public/img/icons/exit.png' alt='<?=$lang['EXIT']?>'/> <?=$lang['EXIT']?>
                      </a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->

    </nav>
    <div class='container'>
            <br/><br/><br/><br/><br/><br/>
            <div class='row'>
                <div class='col-lg-12 text-center'>
                    <h1><?php echo $lang[$vars['Title']]; ?></h1>
                </div>
            </div>

        <?php } ?>
