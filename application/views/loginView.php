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
<link href='<?php echo $helper->urlBase(); ?>/public/css/login.css' rel='stylesheet'>

    <div class='container'>
        <div class='row'>
            <div class='col-sm-4 col-md-4  col-md-offset-4'>
                <h1 class='text-center login-title'><?php echo $lang['PAGE_TITLE']; ?></h1>

                <div style="width: 100%; padding: 0 25% 0 25%;">
                  <p>
                    <a href="?lang=es"><?php echo $lang['ES'];?></a> | <a href="?lang=en"><?php echo $lang['EN'];?></a>
                  </p>
                  <img src="<?php echo $helper->urlBase(); ?>/public/img/logo.png" width="100%" alt="Kolumba mail logo" />



                  <div class='account-wall col-md-4' style="padding: 25px 0 25px 0">
                    <div id='gSignInWrapper'>
                      <p>
                        <span class='label'><?php echo $lang['LOGIN_WITH'];?></span>
                        <a href='<?php echo $helper->url('account','login_google'); ?>' style="color: #fff;">
                            <div id='customBtn' class='customGPlusSignIn'>
                                <span class='icon'></span>
                                <span class='buttonText'>Google</span>
                            </div>
                        </a>
                      </p>
                    </div>
                  </div>
                </div>
            </div>

            <div class='text-center blocks'>
              <div class='col-md-4'>
                <h2><?php echo $lang['LOGIN_BLOCK_1_TITLE'];?></h2>
                <p>
                  <?php echo $lang['LOGIN_BLOCK_1_TEXT'];?>
                </p>
              </div>
              <div class='col-md-4'>
                <h2><?php echo $lang['LOGIN_BLOCK_2_TITLE'];?></h2>
                <p>
                  <?php echo $lang['LOGIN_BLOCK_2_TEXT'];?>
                </p>
              </div>
              <div class='col-md-4'>
                <h2><?php echo $lang['LOGIN_BLOCK_3_TITLE'];?></h2>
                <p>
                  <?php echo $lang['LOGIN_BLOCK_3_TEXT'];?>
                </p>
              </div>
            </div>
        </div>
    </div>


