<?php


$AJAX_INCLUDE = 1;

include "../../../inc/includes.php";
include_once("../inc/kanbanTemplate.class.php");
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_GET['kanban_curr_item_id']) && ($_GET['kanban_curr_item_id'] > 0)) {
   $kanbanTemplate = new KanbanTemplate();
   $result = $kanbanTemplate->getDataItemKanban($_GET['kanban_curr_item_id']);
   echo json_encode($result);
}
else{

  if(isset($_POST['requisicao'])){
      $locais= [];
      $tipo =[];
      $categoria=[];
      if(isset($_POST['filtroLocal'])){
         $locais = $_POST['filtroLocal'];
      }
      if(isset($_POST['filtroTipo'])){
         $tipo = $_POST['filtroTipo'];
      }
      if(isset($_POST['filtroCategoria'])){
         $categoria = $_POST['filtroCategoria'];
      }

      if($_POST['requisicao']== 'filtrar'){
         $kanbanTemplate = new KanbanTemplate();
         $result = $kanbanTemplate->getKanbanBoardsFilter($_POST['filtroAbertoPorMim'],$_POST['filtroAtribuidoParaMim'],$_POST['filtroAtribuidoParaMinhaEquipe'], $locais,$tipo,$categoria,$_POST['dataInicioAbertura'],$_POST['dataFinalAbertura']);
         echo json_encode($result);      
      }
  }else{
      $kanbanTemplate = new KanbanTemplate();
      $result = $kanbanTemplate->getKanbanBoards();
      echo json_encode($result);
  }    
}