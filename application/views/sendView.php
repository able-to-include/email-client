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
    <script type='text/javascript' src='<?php echo $helper->urlBase(); ?>/public/js/popup_attach.js'></script>
    <script type='text/javascript'>
$(document).ready(function (){

  $('#templateReceived').click(function() {
    template = "<?php echo $lang['TEMPLATE_1_TEXT']; ?>";
    cambiarPlantilla(template);
  });

  $('#templateNotUnderstand').click(function() {
    template = "<?php echo $lang['TEMPLATE_2_TEXT']; ?>";
    cambiarPlantilla(template);
  });

  $('#templateAttachedFailure').click(function() {
    template = "<?php echo $lang['TEMPLATE_3_TEXT']; ?>";
    cambiarPlantilla(template);
  });
});

function cambiarPlantilla(template) {
  var oldValue = $('#textareaMessage').val();

  var separator = "----------------------------------------------------";
  var res = oldValue.split(separator);

  var oldText = '';
  for (var i = 1; i< res.length;i++) {
    oldText += res[i];
  }

  $('#textareaMessage').val(template+ "\r\n" + separator + oldText);
}
    </script>

    <div class='overlay-container'>
        <div class='window-container zoomin'>
            <h3>AVISO</h3>
            <br>
            <p id='textPopup'> </p>
            <br>
            <hr>
            <div id='cancel' class='page-scroll  btn btn-success btn-lg' style='width:50%'>
                <span class="glyphicon glyphicon-ok"></span> CONTINUAR
            </div>
        </div>
    </div>

    <form action='<?php echo $helper->url('mails','send'); ?>' method='post' enctype='multipart/form-data'>
        <div class="tableInformation tableSend">
            <table>
                <tr>
                    <td></td>
                    <td><?php echo $lang['WRITE_NEW_MESSAGE']; ?></td>
                    <td>
                        <label for="file-upload" class="custom-file-upload page-scroll btn btn-default btn-attach" title="<?php echo $lang['ATTACH_FILE'];?>" >
                            <img id='bAttach' class='eye' src='<?php echo $helper->urlBase(); ?>/public/img/icons/clip_white.png' alt="Clip" />
                        </label>
                        <input id="file-upload" type="file" name="attachments[]" multiple data-type='zoomin'
                        accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                            application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf,
                            image/*, audio/*, video/*,
                            application/x-tar, .rar,
                            application/zip, application/x-zip, application/x-zip-compressed, application/x-compress, application/x-compressed, application/x-gzip"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><input class="inputText" type="email" name="mail" placeholder="<?php echo $lang['TO']; ?>" value="<?php echo isset($vars['RE']) ? $vars['Mail']['to'] :  '' ?>"></td>
                </tr>
                <tr>
                    <td colspan="3"><input class="inputText" type="text" name="subject" placeholder="<?php echo $lang['SUBJECT']; ?>" value="<?php echo isset($vars['RE']) ? 'RE: '.$vars['Mail']['subject'] :  '' ?>"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <button id="templateReceived" type="button" class="btn btn-success" aria-label="Left Align">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?php echo $lang['TEMPLATE_1_TITLE']; ?>
                        </button>
                        <button id="templateNotUnderstand" type="button" class="btn btn-danger" aria-label="Left Align">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> <?php echo $lang['TEMPLATE_2_TITLE']; ?>
                        </button>
                        <button id="templateAttachedFailure" type="button" class="btn btn-danger" aria-label="Left Align">
                            <span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span> <?php echo $lang['TEMPLATE_3_TITLE']; ?>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><textarea id="textareaMessage" name="body" placeholder="<?php echo $lang['TYPE_MESSAGE']; ?>"><?php echo isset($vars['RE']) ? "\n\n\n----------------------------------------------------\n".$vars['Mail']['body'] :  '';  ?></textarea></td>
                </tr>
            </table>
        </div>
        <div class="form-group col-lg-12">
          <button type="submit" class="btn btn-success btn-lg" style="width:100%" title="<?php echo $lang['SEND_MESSAGE'];?>">
            <span class="glyphicon glyphicon-send"></span> <?php echo $lang['SEND']; ?>
          </button>
        </div>
    </form>
</div>
