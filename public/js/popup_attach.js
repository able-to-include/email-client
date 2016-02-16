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
$(document).ready(function (){
   $('#file-upload').on("change", function (e) {
    var totalSize=0;
    length = this.files.length;
    for (i = 0; i < length; ++i) {
        totalSize += this.files[i].size;
     //console.log(this.files[i]);
    }
    var totalSize = totalSize/1024/1024;
    if(totalSize>10){
      totalSize = Math.round(totalSize * 1000) / 1000;
        $("#file-upload").replaceWith($("#file-upload").clone());
        $("#textPopup").html("El tamaño ("+totalSize+" MB) supera los 10 MB permitidos.")

        type = $(this).attr('data-type');    
        $('.overlay-container').fadeIn(function() {
          
          window.setTimeout(function(){
            $('.window-container.'+type).addClass('window-container-visible');
          }, 100);
        });
    }
  });
  $('#cancel').click(function() {
    $('.overlay-container').fadeOut().end().find('.window-container').removeClass('window-container-visible');
  });
});