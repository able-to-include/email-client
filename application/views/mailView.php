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

function quitar_tildes($cadena) {
$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
$texto = str_replace($no_permitidas, $permitidas ,$cadena);
return $texto;
}

function normalizeEmail($address) {
  $address = str_replace("<","&lt;",$address);
  $address = str_replace(">","&gt;",$address);
  return $address;
}
?>
    <script type='text/javascript' src='<?php echo $helper->urlBase(); ?>/public/js/popup_delete.js'></script>
    <script type="text/javascript" src="<?php echo $helper->urlBase(); ?>/public/js/audio_popup.js"></script>
    <script type='text/javascript'>
        function getText(){
            var texto = '<?php echo $lang['EMPTY_MESSAGE']; ?>';
                <?php
                foreach ($vars['Mail']['Body'] as $value) {
                    if(strcmp($value['mime'], 'text/plain') == 0){
                        $aux = preg_replace('/<\/?[^>]+(>|$)/','',$value['content']);
                        $aux = preg_replace("/\r\n|\r|\n/",' ',$aux);
                        $texto.= $aux;
                        ?>
                        texto = <?php print ('"'.$texto.'"'); ?>;
                        <?php
                    }
                }
                ?>
                return texto;
        }

        $(document).ready(function(){
            //text2speech
            $('.audioplay').click(function() {
                var texto = document.getElementById("p"+this.id).innerHTML;

                $.ajax({
                    url: '<?php echo TEXT2SPEECH ?>',
                    type: 'GET',
                    headers: {
                      'Origin': 'http://kolumba.eu'
                    },
                    async: true,
                    dataType : 'json',
                    data: 'text='+texto+'&language=<?php echo $lang['TEXT2SPEECH_LANGUAGE']; ?>',

                    beforeSend: function() {
                        $("#dvLoading").fadeIn("slow");
                    },
                     complete: function() {
                        $("#dvLoading").fadeOut("slow");
                    },

                    success: function(json) {
                        $('#audio').attr('src',json.audioSpeech);

                        if (running_iOS()) {
                          document.getElementById('audioDiv').style.display = "block";
                          document.getElementById('playPause').innerHTML = '<span class="glyphicon glyphicon-play"></span> <?php echo $lang['PLAY'];?>';
                        } else {
                          document.getElementById('audio').play();
                          document.getElementById('playPause').innerHTML = '<span class="glyphicon glyphicon-pause"></span> <?php echo $lang['PAUSE'];?>';
                        }
                    },
                    error: function(xhr, status) {
                        console.log(status);
                    }
                });
            });

            //text2picto - Beta
            $('#picto').click(function() {
                var texto = getText();
                $.ajax({
                    url: '<?php echo TEXT2PICTO ?>',
                    type: 'GET',
                    headers: {
                      'Origin': 'http://kolumba.eu'
                    },
                    async: true,
                    dataType : 'json',
                    data: 'text='+texto+'&type=beta&language=<?php echo $lang['TEXT2SPEECH_LANGUAGE']; ?>',
                    beforeSend: function() {
                        $("#dvLoading").fadeIn("slow");
                    },
                    complete: function() {
                        $("#dvLoading").fadeOut("slow");
                    },
                    success: function(json) {
                        var newBody ='';
                        json.pictos.forEach(function(item){
                            if(item.indexOf("http") > -1) {
                                newBody += '<img src="'+item+'">'+' ';
                            } else {
                                 newBody +=  '<font size="6">'+item+'</font>'+' ';
                            }
                        });
                        document.getElementById("mailBody").innerHTML = texto + "<br/><hr/><br/>" + newBody;
                    },
                    error: function(xhr, status) {
                        console.log(status);
                    }
                });
            });

            //text2picto - Sclera
            $('#picto2').click(function() {
                var texto = getText();

                $.ajax({
                    url: '<?php echo TEXT2PICTO ?>',
                    type: 'GET',
                    headers: {
                      'Origin': 'http://kolumba.eu'
                    },
                    async: true,
                    dataType : 'json',
                    data: 'text='+texto+'&type=sclera&language=<?php echo $lang['TEXT2SPEECH_LANGUAGE']; ?>',
                    beforeSend: function() {
                        $("#dvLoading").fadeIn("slow");
                    },
                    complete: function() {
                        $("#dvLoading").fadeOut("slow");
                    },
                    success: function(json) {
                        var newBody ='';
                        json.pictos.forEach(function(item){
                            if(item.indexOf("http") > -1) {
                                newBody += '<img width="150px" src="'+item+'" />'+' ';
                            } else {
                                 newBody += '<font size="6">'+item+'</font>'+' ';
                            }
                        });
                        document.getElementById("mailBody").innerHTML = texto + "<br/><hr/><br/>" + newBody;
                    },
                    error: function(xhr, status) {
                        console.log(status);
                    }
                });
            });

            //simplext
            $('#simplext').click(function() {
                var texto = getText();
                $.ajax({
                    url: '<?php echo SIMPLEXT ?>',
                    type: 'GET',
                    headers: {
                      'Origin': 'http://kolumba.eu'
                    },
                    async: true,
                    dataType : 'json',
                    data: 'text='+texto+'&language=<?php echo $lang['TEXT2SPEECH_LANGUAGE']; ?>',
                    beforeSend: function() {
                        $("#dvLoading").fadeIn("slow");
                    },
                     complete: function() {
                        $("#dvLoading").fadeOut("slow");
                    },
                    success: function(json) {
                        document.getElementById("mailBody").innerHTML = json.textSimplified;
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
<?php
$stringFrom = "";
$stringTo = "";
$stringCc = "";
$stringReplyTo = "";

$stringFrom = quitar_tildes(str_replace('\'', '',str_replace('"', '',str_replace('>','',str_replace('<','',$vars['Mail']['From'])))));
$stringTo = quitar_tildes(str_replace('\'', '',str_replace('"', '',str_replace('>','',str_replace('<','',$vars['Mail']['To'])))));

if (isset($vars['Mail']['Cc'])) {
  $stringCc = quitar_tildes(str_replace('\'', '',str_replace('"', '',str_replace('>', '', str_replace('<', '', $vars['Mail']['Cc'])))));
}
if (isset($vars['Mail']['Reply-To'])) {
  $stringReplyTo = quitar_tildes(str_replace('\'', '',str_replace('"', '',str_replace('>', '', str_replace('<', '', $vars['Mail']['Reply-To'])))));
}


if (strlen($stringReplyTo) == 0) {
  preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i',$stringFrom, $from);
} else {
  $from = array();
  $from[0][0] = $stringReplyTo;
}
preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i',$stringTo, $to);
preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i',$stringCc, $cc);

?>
    <div class='row'>
        <div>
            <div class='form-group kolumba-col-lg-4'>
                <div style='text-align: center;'>
                    <form action='<?php echo $helper->url('mails','newMail', 'RE'); ?>' method='post'>
                        <button  type='submit' class='btn btn-success btn-lg' data-type='zoomin' title="<?php echo $lang['REPLY']; ?>">
                            <img class='icono' src='<?php echo $helper->urlBase(); ?>/public/img/icons/reply.png' alt="<?php echo $lang['REPLY']; ?>" /> <?php echo $lang['REPLY']; ?>
                        </button>

                        <input type="hidden" name="to" value="<?php
                                                                  if (isset($vars['INBOX'])) {
                                                                    foreach ($from as  $value) {
                                                                      echo $value[0].', ';
                                                                    }
                                                                  } else {
                                                                    echo $to[0][0];
                                                                  }
                                                                    ?>">
                        <input type="hidden" name="subject" value="<?php echo $vars['Mail']['Subject']; ?>">
                        <input type="hidden" name="body" value="<?php   foreach ($vars['Mail']['Body'] as $value) {
                                                                            if(strcmp($value['mime'], 'text/plain') == 0){
                                                                                print(str_replace('"', '', $value['content']));
                                                                            }
                                                                        }?>">
                    </form>
                </div>
            </div>

            <div class='form-group kolumba-col-lg-4'>
                <div style='text-align: center;'>
                    <form action='<?php echo $helper->url('mails','newMail', 'RE'); ?>' method='post'>
                        <button  type='submit' class='btn btn-success btn-lg' style='width:90%' data-type='zoomin'  title="<?php echo $lang['REPLY_ALL']; ?>">
                            <img class='icono' src='<?php echo $helper->urlBase(); ?>/public/img/icons/reply-all.png' alt="<?php echo $lang['REPLY_ALL']; ?>" /> <?php echo $lang['REPLY_ALL']; ?>
                        </button>

                        <input type="hidden" name="to" value="<?php
  if (isset($vars['INBOX'])) {
    foreach ($from as  $value) {
      echo $value[0].', ';
    }
  }
  if (isset($cc)) {
    foreach ($cc[0] as  $value) {
      echo $value.', ';
    }
  }
  foreach ($to[0] as  $value) {
    echo $value.', ';
  }
                                                                  ?>">
                        <input type="hidden" name="subject" value="<?php echo $vars['Mail']['Subject']; ?>">
                        <input type="hidden" name="body" value="<?php   foreach ($vars['Mail']['Body'] as $value) {
                                                                            if(strcmp($value['mime'], 'text/plain') == 0){
                                                                                print(str_replace('"', '', $value['content']));
                                                                            }
                                                                        }?>">

                    </form>
                </div>
            </div>



            <div class='form-group kolumba-col-lg-4'>
                <div style='text-align: center;'>
                    <button id='delete' type='submit' class='btn btn-danger btn-lg' style='width:90%' data-type='zoomin'   title="<?php echo $lang['DELETE']; ?>">
                        <img class='icono' src='<?php echo $helper->urlBase(); ?>/public/img/icons/trash.png' alt="<?php echo $lang['DELETE']; ?>" /> <?php echo $lang['DELETE']; ?>
                    </button>
                </div>
            </div>
        </div>

        <div class='overlay-container'>
            <div class='window-container zoomin'>
                <span style="font-size: 130%; text-transform: uppercase; font-weight: bold;"><?=$lang['DELETE']?></span>
                <br>
                <p><?php echo $lang['ARE_YOU_SURE_YOU_WANT_TO_DELETE_MESSAGE']; ?></p>
                <br>
                <hr>
                <a href='<?php echo $helper->url('mails', 'delete', $vars['Mail']['Id'], isset($vars['SENT']) ? 'SENT' : 'INBOX'); ?>'>
                    <div class='page-scroll btn btn-danger btn-lg' style='width:40%'>
                        <span class="glyphicon glyphicon-trash"></span> <?php echo $lang['DELETE']; ?>
                    </div>
                </a>
                <div id='cancel' class='page-scroll  btn btn-success btn-lg' style='width:40%'>
                    <span class="glyphicon glyphicon-remove"></span> <?php echo $lang['CANCEL']; ?>
                </div>
            </div>
        </div>


        <hr class='barra'>

        <div>

            <p><?php echo '<strong>'.$lang['FROM'].':</strong> '.normalizeEmail($vars['Mail']['From']); ?> </p>
            <hr class='barra' />

            <p><?php echo '<strong>'.$lang['TO'].'</strong>: '.normalizeEmail($vars['Mail']['To']);  ?> </p>
            <hr class='barra' />


            <p><strong><?php echo $lang['SUBJECT']; ?>:</strong> <?php echo $vars['Mail']['Subject']; ?><?php echo isset($vars['Mail']['attachments']) ? '<img class="icono" src="'.$helper->urlBase().'/public/img/icons/clip.png" alt="Clip" />': '';?></p>
            <hr class='barra'>
            <p><strong><?php echo $lang['DATE']; ?>:</strong> <?php echo $vars['Mail']['Date']; ?></p>
            <hr class='barra'>
            <div class='col-lg-12' id='sidebar-left'>
                <div style='text-align: center;' class='kolumba-col-lg-4 item'>
                    <button type='submit' id='simplext' class='btn btn-success btn-lg' style='width:90%;' title='<?php echo $lang['SIMPLE_TEXT']; ?>'>
                        <span class="glyphicon glyphicon-book"></span> <?php echo $lang['SIMPLE_TEXT']; ?>
                    </button>
                </div>

                <?php
                  if (USE_TEXT_TO_PICTO) {
                    echo '
                <div style="text-align: center;" class="kolumba-col-lg-4 item">
                    <button type="submit" id="picto" class="btn btn-success btn-lg" style="width:90%"  title="'.$lang['PICTO'].'">
                        <span class="glyphicon glyphicon-picture"></span> '.$lang['PICTO'].'
                    </button>
                </div>
                <div style="text-align: center;" class="kolumba-col-lg-4 item">
                    <button type="submit" id="picto2" class="btn btn-success btn-lg" style="width:90%"  title="'.$lang['PICTO_2'].'">
                        <span class="glyphicon glyphicon-picture"></span> '.$lang['PICTO_2'].'
                    </button>
                </div>';
                  }
                ?>
            </div>

            <div class='col-lg-12'>
                <div id='mailBody'>
                    <?php
                    foreach ($vars['Mail']['Body'] as $value) {
                        if(strcmp($value['mime'], 'text/plain') == 0){
                            $mailBodyArray = explode('<br/>' , $value['content']);
                            $i = 0;
                            foreach($mailBodyArray as $line) {
                              if (strlen($line) > 2) {
                                echo "
                    <div>
                      <span id='p".$i."'>
                        ".str_replace('#','',$line)."
                      </span>";
                                echo "
                      <div class='marginButton btn btn-success'>
                        <span class='glyphicon glyphicon-volume-up audioplay icono' id='".$i."'></span>
                      </div>
                    </div>";
                              }
                            $i++;
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <br/><br/><br/><br/><br/><br/>
    </div>
    <div class='row div-files'>
        <div class='col-lg-3'></div>
        <?php
        $iconPerRow = 9;
        foreach ($vars['Mail']['attachmentsArray'] as $key => $value) {
            if($iconPerRow == 0) {
                $iconPerRow = 9;
                ?>
                 <div class='col-lg-3'></div>
            <?php
            }
            ?>
        <div class='col-lg-1 div-file'>
            <a href="data:application/octet-stream;charset=utf-8;base64,<?php echo $value['content']?>" download='<?php echo $key?>'>
                <img class='eye' src='<?php echo $helper->urlBase(); ?>/public/img/icons/files/<?php echo $value['ext']?>.png' alt="File"><br>
                <?php echo $key?>
            </a>
        </div>
        <?php
        $iconPerRow--;
        }
        ?>
    </div>
    <br/>
</div>

