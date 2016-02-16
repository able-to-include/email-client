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
<script type="text/javascript">
  $(document).ready(function (){
    $('#exampleModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var name = button.data('name') //Extract info from data-name attribute
      var mail = button.data('mail') //Extract info from data-mail attribute
      var self = button.data('self') //Extract info from data-self attribute

      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-footer #mail').val(mail)
      modal.find('.modal-footer #name').val(name)
      modal.find('.modal-footer #selfURL').val(self)
    });
  });
</script>

<script type="text/javascript">

</script>

    <div class='tableInformation tableContactos'>

      <table>
            <tr>
                <td><?php echo $lang['CONTACT_NAME'];?></td>
                <td><?php echo $lang['CONTACT_EMAIL'];?></td>
                <td><?php echo $lang['SAVE'];?></td>
                <td><?php echo $lang['DELETE'];?></td>
            </tr>

            <tr>
              <form action='<?php echo $helper->url('contacts','add'); ?>' method='post'>
                <td><input class="inputText" type="text" name="name" placeholder="<?php echo $lang['CONTACT_NAME'];?>"></td>
                <td><input class="inputText" type="email" name="mail" placeholder="<?php echo $lang['EMAIL_PLACEHOLDER'];?>"></td>
                <td class="textCenter" colspan="2"><button type="submit" name='add' class="btn btn-success"><?php echo $lang['ADD_CONTACT'];?></button></td>
              </form>
            </tr>

            <?php
            $i = 0;
            foreach ($vars['Contacts'] as $key => $value) { //var_dump($value); ?>
                <tr>
                  <form action='<?php echo $helper->url('contacts','updateOrDelete'); ?>' method='post'>
                    <td><input class="inputText" type="text" name="name" value="<?php echo $value->name; ?>"></td>
                    <td><input class="inputText" type="email" name="mail" value="<?php echo $value->email; ?>"></td>
                    <td class="textCenter">
                      <input type="hidden" name='selfURL' id="selfURL" value="<?php echo $value->selfURL; ?>"/>
                      <button type="submit" name='update' class="btn btn-success" title="<?php echo $lang['SAVE_CONTACT'];?>"><span class="glyphicon glyphicon-ok"></span></button>
                    </td>
                    <td class="textCenter">

                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-self="<?php echo $value->selfURL; ?>" data-name="<?php echo $value->name; ?>" data-mail="<?php echo $value->email; ?>"><span class="glyphicon glyphicon-trash"></span></button>

                    </td>
                  </form>
                </tr>
            <?php
              $i++;
            }
            ?>
      </table>

    </div>
    <div>&nbsp;</div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="modal-title" style="font-size: 130%; text-transform: uppercase; font-weight: bold;"><?=$lang['DELETE']?></span>
          </div>
          <div class="modal-body">
              <p><?=$lang['ARE_YOU_SURE_YOU_WANT_TO_DELETE_CONTACT']?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=$lang['CANCEL']?></button>
            <form action='<?php echo $helper->url('contacts','updateOrDelete'); ?>' method='post'>
              <!--<button type="button" class="btn btn-primary">Send message</button>-->
              <input type="hidden" id="name" name="name" value="">
              <input type="hidden" id="mail" name="mail" value="">
              <input type="hidden" name='selfURL' id="selfURL" value=''/>
              <button name="delete" type="submit" class="btn btn-danger" title="<?php echo $lang['DELETE_CONTACT'];?>"><span class="glyphicon glyphicon-trash"></span> <?php echo $lang['DELETE_CONTACT'];?></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
