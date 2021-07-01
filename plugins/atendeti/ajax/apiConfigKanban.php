<?php


$AJAX_INCLUDE = 1;

include "../../../inc/includes.php";
include_once("../inc/kanban-config.class.php");
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();
$KanbanConfig = new KanbanConfig();


    if (isset($_GET['requisicao'])){
        if($_GET['requisicao']=="get_config"){

            $result = $KanbanConfig->getCofigKanban();
            echo json_encode($result);
        }        

    }
    if (isset($_POST['requisicao'])){
            if($_POST['requisicao']=="update_config"){
                
                $result = $KanbanConfig->setUpdateConfigKanban($_POST);
                echo json_encode($result);
            }
    }