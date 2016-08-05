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


    <div class="browser-landing" id="main">

    <div id="div_start">
        <button id="start_button" onclick="startButton(event)"><img alt="Start" id="start_img" src="/public/img/content/mic.gif"></button>
    </div>

    <div class="compact marquee">
        <div id="info">
            <p id="info_start">
                <?php echo $lang['WEB_SPEECH_API_INFO_START']; ?>
            </p>
            <p id="info_speak_now" style="display:none">
                <?php echo $lang['WEB_SPEECH_API_INFO_SPEAK_NOW']; ?>
            </p>
            <p id="info_no_speech" style="display:none">
                <?php echo $lang['WEB_SPEECH_API_INFO_NO_SPEECH']; ?>
            </p>
            <p id="info_no_microphone" style="display:none">
                <?php echo $lang['WEB_SPEECH_API_INFO_NO_MICROPHONE']; ?>
            </p>
            <p id="info_allow" style="display:none">
                <?php echo $lang['WEB_SPEECH_API_INFO_ALLOW']; ?>
            </p>
            <p id="info_denied" style="display:none">
                <?php echo $lang['WEB_SPEECH_API_INFO_DENIED']; ?>
            </p>
            <p id="info_blocked" style="display:none">
                <?php echo $lang['WEB_SPEECH_API_INFO_BLOCKED']; ?>
            </p>
            <p id="info_upgrade" style="display:none">
                <?php echo $lang['WEB_SPEECH_API_INFO_UPGRADE']; ?>
            </p>
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
                    <td colspan="3">
                      <textarea id="textareaMessage" name="body" placeholder="<?php echo $lang['TYPE_MESSAGE']; ?>"><?php echo isset($vars['RE']) ? "\n\n\n----------------------------------------------------\n".$vars['Mail']['body'] :  '';  ?></textarea>
                    </td>
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


    <script src="http://www.google.com/intl/en/chrome/assets/common/js/chrome.min.js"></script>
    <script>

showInfo('info_start');


var final_transcript = '';
var recognizing = false;
var ignore_onend;
var start_timestamp;
if (!('webkitSpeechRecognition' in window)) {
  upgrade();
} else {
  start_button.style.display = 'inline-block';
  var recognition = new webkitSpeechRecognition();
  recognition.continuous = true;
  recognition.interimResults = true;

  recognition.onstart = function() {
    recognizing = true;
    showInfo('info_speak_now');
    start_img.src = '/public/img/content/mic-animate.gif';
  };

  recognition.onerror = function(event) {
    if (event.error == 'no-speech') {
      start_img.src = '/public/img/content/mic.gif';
      showInfo('info_no_speech');
      ignore_onend = true;
    }
    if (event.error == 'audio-capture') {
      start_img.src = '/public/img/content/mic.gif';
      showInfo('info_no_microphone');
      ignore_onend = true;
    }
    if (event.error == 'not-allowed') {
      if (event.timeStamp - start_timestamp < 100) {
        showInfo('info_blocked');
      } else {
        showInfo('info_denied');
      }
      ignore_onend = true;
    }
  };

  recognition.onend = function() {
    recognizing = false;
    if (ignore_onend) {
      return;
    }
    start_img.src = '/public/img/content/mic.gif';
    if (!final_transcript) {
      showInfo('info_start');
      return;
    }
    showInfo('');
    if (window.getSelection) {
      window.getSelection().removeAllRanges();
      var range = document.createRange();
      //range.selectNode(document.getElementById('final_span'));
      range.selectNode(document.getElementById('textareaMessage'));
      window.getSelection().addRange(range);
    }
  };

  recognition.onresult = function(event) {
    var interim_transcript = '';
    if (typeof(event.results) == 'undefined') {
      recognition.onend = null;
      recognition.stop();
      upgrade();
      return;
    }
    for (var i = event.resultIndex; i < event.results.length; ++i) {
      if (event.results[i].isFinal) {
        final_transcript += event.results[i][0].transcript;
      } else {
        interim_transcript += event.results[i][0].transcript;
      }
    }
    final_transcript = capitalize(final_transcript);


    //interim_span.innerHTML = linebreak(interim_transcript);
    textareaMessage.innerHTML = linebreak(interim_transcript);
    //final_span.innerHTML = linebreak(final_transcript);
    textareaMessage.innerHTML = linebreak(final_transcript);
  };
}

function upgrade() {
  start_button.style.visibility = 'hidden';
  showInfo('info_upgrade');
}

var two_line = /\n\n/g;
var one_line = /\n/g;
function linebreak(s) {
  return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
}

var first_char = /\S/;
function capitalize(s) {
  return s.replace(first_char, function(m) { return m.toUpperCase(); });
}

function startButton(event) {
  if (recognizing) {
    recognition.stop();
    return;
  }
  final_transcript = '';
  recognition.lang = <?php echo "'".$lang['WEB_SPEECH_API_LANG']."'"; ?>;
  recognition.start();
  ignore_onend = false;
  //final_span.innerHTML = '';
  textareaMessage.innerHTML = '';
  //interim_span.innerHTML = '';
  textareaMessage.innerHTML = '';
  start_img.src = '/public/img/content/mic-slash.gif';
  showInfo('info_allow');
  start_timestamp = event.timeStamp;
}

function showInfo(s) {
  if (s) {
    for (var child = info.firstChild; child; child = child.nextSibling) {
      if (child.style) {
        child.style.display = child.id == s ? 'inline' : 'none';
      }
    }
    info.style.visibility = 'visible';
  } else {
    info.style.visibility = 'hidden';
  }
}

    </script>
