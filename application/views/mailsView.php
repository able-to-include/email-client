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

function normalizeEmail($address) {
  $address = str_replace("<","&lt;",$address);
  $address = str_replace(">","&gt;",$address);
  $address = str_replace("'","&#039;", $address);
  return $address;
}

function buildAudioPlay($selectedLanguage, $inbox, $item, $message, $from, $to, $withSubject, $theDay) {
  $audioPlayText = '';
  $audioPlayText .= $message . ' ';

  if(isset($inbox)){
    $audioPlayText .= $from.': ';
  } else {
    $audioPlayText .= $to.': ';
  }

  if(isset($item['From'])) {
    $audioPlayText .= normalizeEmail($item['From']);
  } else {
    $audioPlayText .= normalizeEmail($item['To']);
  }

  $audioPlayText .= ', '. $withSubject . ': ';


  $audioPlayText .= str_replace('#','',str_replace('"','',$item['Subject']));


  if (strcmp($selectedLanguage, 'es') == 0) {
    $audioPlayText .= ' '. $theDay . ' ' . $item['DateAudioES'];
  } else if (strcmp($selectedLanguage, 'en') == 0) {
    $audioPlayText .= ' '. $theDay . ' ' . $item['DateAudioEN'];
  }
  return $audioPlayText;
}
?>
    <script type="text/javascript" src="<?php echo $helper->urlBase(); ?>/public/js/audio_popup.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.audioplay').click(function() {
                var texto = $(this).attr('title');
                $.ajax({
                    url: '<?php echo TEXT2SPEECH; ?>',
                    type: 'GET',
                    headers: {
                      'Origin': 'http://kolumba.eu'
                    },
                    async: true,
                    dataType : 'json',
                    data: 'text='+texto+'&language=<?php echo $lang['TEXT2SPEECH_LANGUAGE'];?>',
                    success: function(json) {
                        $('#audio').attr('src',json.audioSpeech);
                        if (running_iOS()) {
                          document.getElementById('audioDiv').style.display = "block";
                          document.getElementById('playPause').innerHTML = '<span class="glyphicon glyphicon-play"></span> <?php echo $lang['PLAY'];?>';
                        } else {
                          document.getElementsByTagName('audio')[0].play();
                          document.getElementById('playPause').innerHTML = '<span class="glyphicon glyphicon-pause"></span> <?php echo $lang['PAUSE'];?>';
                        }
                    },
                    error: function(xhr, status) {
                        console.log(status);
                    }
                });
            });
        });
    </script>

    <div id="audioDiv" class="audio-player">
      <audio id="audio" src="/public/beep.mp3"></audio>
      <a id="playPause" href="javascript:playPause('<?php echo $lang['PLAY'];?>', '<?php echo $lang['PAUSE'];?>');" class="btn btn-primary" style="float: left;"><span class="glyphicon glyphicon-play"></span> <?php echo $lang['PLAY'];?></a>
      <a href="javascript:closePopUp();" class="btn btn-danger" style="float: right;">&times; <?php echo $lang['CLOSE'];?></a>
    </div>

    <div class='tableInformation tableRecibidos'>
        <table>

            <tr>
                <td></td>
                <td><?php echo isset($vars['INBOX']) ? $lang['FROM'].':' : $lang['TO'].':';  ?></td>
                <td><?=$lang['SUBJECT']?>:</td>
                <td><?=$lang['DATE']?>:</td>
                <td></td>
            </tr>
            <?php
            $action = isset($vars['INBOX']) ? 'INBOX' : 'SENT';

            // $listado es una variable asignada desde el controlador ItemsController.
            $i = 0;
            foreach ($vars['Mails'] as $key => $item) { ?>
                <tr <?php echo isset($item['UNREAD']) ? 'class="notRead"' : ''; ?> onclick="document.location.href = '<?php echo $helper->url('mails', 'get', $action, $key); ?>';" >
                    <td onclick="event.stopImmediatePropagation();">
                        <div class='page-scroll marginButton btn btn-success btn-lg' title="<?php echo $lang['LISTEN_EMAIL_DESCRIPTION']; ?>">
                          <span class="glyphicon glyphicon-volume-up audioplay" title="<?php echo buildAudioPlay($lang['SELECTED_LANGUAGE'], $vars['INBOX'], $item, $lang['MESSAGE'], $lang['FROM'], $lang['TO'], $lang['WITH_SUBJECT'], $lang['THE_DAY']); ?>"></span>
                        </div>


                    </td>
                    <td style="cursor: pointer; cursor: hand;"><?php echo isset($item['From']) ? normalizeEmail($item['From']) : normalizeEmail($item['To']);  ?></td>
                    <td style="cursor: pointer; cursor: hand;"><?php echo $item['Subject'];
                     echo isset($item['attachments']) ? '<img class="icono" src="'.$helper->urlBase().'/public/img/icons/clip.png" alt="Clip" />': '';?></td>
                    <td style="cursor: pointer; cursor: hand;"><?php echo $item['Date']?></td>
                    <td onclick="event.stopImmediatePropagation();">

                      <div style='text-align: center;'>
                        <button id='delete<?php echo $i; //Utilizamos $i para no repetir popups ?>' type='submit' class='btn btn-danger btn-lg' data-type='zoomin' title="<?php echo $lang['DELETE_MESSAGE']; ?>">
                          <span class="glyphicon glyphicon-trash"></span>
                        </button>
                      </div>

                      <div class='overlay-container overlay-container<?php echo $i; ?>'>
                        <div class='window-container zoomin'>
                          <span style="font-size: 130%; text-transform: uppercase; font-weight: bold;"><?=$lang['DELETE']?></span>
                          <br>
                          <p><?=$lang['ARE_YOU_SURE_YOU_WANT_TO_DELETE_MESSAGE']?></p>
                          <br>
                          <hr>
                          <a href='<?php echo $helper->url('mails', 'delete', $key, $action);?>'>
                            <div class='page-scroll btn btn-danger btn-lg' style='width:40%' title="<?php echo $lang['DELETE_MESSAGE'];?>">
                              <span class="glyphicon glyphicon-trash"></span> <?=$lang['DELETE']?>
                            </div>
                          </a>
                          <div id='cancel<?php echo $i; ?>' class='page-scroll  btn btn-success btn-lg' style='width:40%' title="<?php echo $lang['CANCEL_DELETION'];?>">
                            <span class="glyphicon glyphicon-remove"></span> <?=$lang['CANCEL']?>
                          </div>
                        </div>
                      </div>
                    </td>
                </tr>
                <?php
                  $i++;
                }
                ?>
        </table>
    </div>

    <div class='row'>
        <div>
            <div class='form-group col-lg-6'>
                <div style='text-align: left;'>
                    <?php
                    if($vars['ActualPage'] >= 1){
                    ?>
                     <a href='<?php echo $helper->url('mails','getAll',isset($vars['SENT']) ? 'SENT' : 'INBOX',$vars['ActualPage']-1); ?>' title="<?php echo $lang['SHOW_PREVIOUS_PAGE'];?>">
                        <div class='page-scroll btn btn-success btn-lg paginate'>
                            <span class="glyphicon glyphicon-menu-left"></span> <?=$lang['PREVIOUS']?>
                        </div>
                    </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class='form-group col-lg-6'>
                <div style='text-align: right;'>
                     <?php if($vars['NextPage']){
                    ?>
                    <a href='<?php echo $helper->url('mails','getAll', isset($vars['SENT']) ? 'SENT' : 'INBOX' ,$vars['ActualPage']+1); ?>' title="<?php echo $lang['SHOW_NEXT_PAGE'];?>">
                        <div class='page-scroll btn btn-success btn-lg paginate'>
                            <?=$lang['NEXT']?> <span class="glyphicon glyphicon-menu-right"></span>
                        </div>
                    </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
