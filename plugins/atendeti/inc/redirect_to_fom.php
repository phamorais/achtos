<?php


include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");

global $DB;

$SendPesqMsg = filter_input(INPUT_GET, 'formSearch', FILTER_SANITIZE_STRING);

//IS Number ?
if (ctype_digit($assunto = filter_input(INPUT_GET, 'formSearch', FILTER_SANITIZE_STRING))) {
    $assunto = filter_input(INPUT_GET, 'formSearch', FILTER_SANITIZE_STRING);
    
         
}elseif (is_string($assunto = filter_input(INPUT_GET, 'formSearch', FILTER_SANITIZE_STRING))) {

    $assunto = filter_input(INPUT_GET, 'formSearch', FILTER_SANITIZE_STRING);
    
    //SQL para selecionar os registros
    $result_msg_cont = "SELECT glpi_plugin_formcreator_forms.id
    FROM glpi_plugin_formcreator_forms 
    WHERE glpi_plugin_formcreator_forms.name = '%".$assunto."%'";



    $resultado_msg = $DB->query($result_msg_cont);
    echo $resultado_msg;

  
}


