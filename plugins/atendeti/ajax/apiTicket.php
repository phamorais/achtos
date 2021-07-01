<?php


$AJAX_INCLUDE = 1;

include "../../../inc/includes.php";
include_once("../inc/ticket-board.class.php");
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();
$TicketBoard = new TicketBoard();
if (isset($_POST['requisicao'])){
    if($_POST['requisicao']=="insert_followup"){
     
        $post=[];
        if(isset($_POST['kanban_curr_item_id'])){
            $post['tickets_id']=$_POST['kanban_curr_item_id'];
        }
        if(isset($_POST['message'])){
            $post['content']=$_POST['message'];
        }
        if(isset($_POST['privado'])){
            $post['privado']=$_POST['privado'];
        }

        $result = $TicketBoard->setFollowup($post);
        echo json_encode($result);
    }        

    if($_POST['requisicao']=="update_status"){
        
        $result = $TicketBoard->setStatus($_POST);
        echo json_encode($result);
    }
}
if (isset($_GET['requisicao'])){
    if($_GET['requisicao']=="get_categories"){
        
        $result = $TicketBoard->getCategories($_POST);
        echo json_encode($result);
    }
    if($_GET['requisicao']=="get_locations"){
        
        $result = $TicketBoard->getLocations($_POST);
        echo json_encode($result);
    }
}    