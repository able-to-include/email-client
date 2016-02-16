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
function playPause(textPlay, textPause) {
  var myAudio = document.getElementsByTagName('audio')[0];
  myAudio.onended = function() {
    yourFunction('<span class="glyphicon glyphicon-play"></span> '+textPlay);
  };
  if (myAudio.paused) {
    myAudio.play();
    yourFunction('<span class="glyphicon glyphicon-pause"></span> '+textPause);
  } else {
    myAudio.pause();
    yourFunction('<span class="glyphicon glyphicon-play"></span> '+textPlay);
  }
}
function closePopUp() {
  document.getElementById('audioDiv').style.display = "none";
}
function running_iOS() {
  return /iPad|iPhone|iPod/.test(navigator.platform);
}
function yourFunction(text) {
  document.getElementById('playPause').innerHTML = text;
}