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
class View {
    function __construct() {
    }

    public function show($name, $vars = array()) {
        //$name es el nombre de nuestra plantilla, por ej, listado.php
        //$vars es el contenedor de nuestras variables, es un arreglo del tipo llave => valor, opcional.

        //Traemos una instancia de nuestra clase de configuracion.
        $config = Config::singleton();

        //Armamos la ruta a la plantilla
        $path = dirname(__FILE__) . '/../views/' . $name;

        //Si no existe el fichero en cuestion, mostramos un 404
        if (file_exists($path) == false) {
            trigger_error ('Template `' . $path . '` does not exist.', E_USER_NOTICE);
            return false;
        }

        //Si hay variables para asignar, las pasamos una a una.
        if(is_array($vars)) {
            foreach ($vars as $key => $value) {
                $key = $value;
            }
        }

        require_once 'helpers.class.php';
        $helper=new Helpers();

        //incluimos la cabecera
        include(dirname(__FILE__). '/../views/templates/header.php');

        //Finalmente, incluimos la plantilla.
        include($path);

         //incluimos el footer
        include(dirname(__FILE__). '/../views/templates/footer.php');
    }
}
?>
