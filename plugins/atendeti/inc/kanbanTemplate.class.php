<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2018 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */
//include ( "../../../inc/ticket.class.php");
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Template for task
 * @since 9.1
**/
class KanbanTemplate extends CommonDropdown {

   private $conection;

   public function __construct(){
      global $DB;
      $this->conection = $DB;
   }
   public function obterQuery(){
     $query = Ticket:: getCriteriaFromProfile();

     echo($query);

   }


   
   public function getDataItemKanban($kanban_curr_item_id){
   
      $restult = $this->conection->request(['SELECT' => [
                                             'glpi_tickets.*',
                                             'glpi_plugin_tag_tags.name as name_tag',
                                             'glpi_plugin_tag_tags.color'
                                          ],'FROM' => 'glpi_tickets',
                                          'LEFT JOIN' => [
                                                         'glpi_plugin_tag_tagitems' => ['FKEY' => ['glpi_plugin_tag_tagitems' => 'items_id', 'glpi_tickets' => 'id']],
                                                         'glpi_plugin_tag_tags' => ['ON' => ['glpi_plugin_tag_tags' => 'id','glpi_plugin_tag_tagitems'=> 'plugin_tag_tags_id']]
                                                         
                                              ]
                                             ,'WHERE' => [ 'glpi_tickets.id' => $kanban_curr_item_id]]);

      $result = $restult->next();      
      $result['content'] = htmlspecialchars_decode($result['content']);
      $result['date'] = date('d/m/Y H:i:s', strtotime($result['date']));
      $result['proprietario']=self::getUsuarioTipoTicket($kanban_curr_item_id,1);
      $result['atribuicao']=self::getUsuarioTipoTicket($kanban_curr_item_id,2);
      $result['acompanhamentos'] = self::getDataItemAcompanhamentos($kanban_curr_item_id);
      //var_dump($result);
      return $result;
   }
   public function getUsuarioTipoTicket($kanban_curr_item_id,$type){
      $restult = $this->conection->request(['SELECT' => [
                                             'glpi_users.*'
                                          ],'FROM' => 'glpi_tickets_users',
                                          'LEFT JOIN' => [
                                             'glpi_users' => ['ON' => ['glpi_users' => 'id','glpi_tickets_users'=> 'users_id']]
                                          ]    
                                          ,'WHERE' => ['tickets_id' => $kanban_curr_item_id,'glpi_tickets_users.type'=>$type ]]);
      $result = $restult->next();
      if ($result["firstname"] && !$result["realname"]) {
         $result["realname"] = $result["firstname"];
         $result["firstname"] = '';
      }

      $firstname = formatUserName(
         $result["id"],
         $result["name"],
         $result["realname"],
         $result["firstname"]
      );
      return [
         'firstname' => $firstname,
      ];
   }

   public function getDataItemAcompanhamentos($kanban_curr_item_id) {
      global $CFG_GLPI;

      /** @var Ticket */
      $item = Ticket::getById($kanban_curr_item_id);
      $timeline    = $item->getTimelineItems();

      $timeline = array_map(function ($item) use ($CFG_GLPI, $kanban_curr_item_id) {
         $type = $item['type'];
         $item = $item['item'];
         $item['type'] = $type;

         $item['date'] = date('d/m/Y H:i:s', strtotime($item['date']));

         if ($type === 'Document_Item') {
            $item['content'] = '';
            if ($item['filename']) {
               $filename = $item['filename'];
               $ext      = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
               $item['content'] .=  "<img src='";
               if (empty($filename)) {
                  $filename = $item['name'];
               }
               if (file_exists(GLPI_ROOT."/pics/icones/$ext-dist.png")) {
                  $item['content'] .=  $CFG_GLPI['root_doc']."/pics/icones/$ext-dist.png";
               } else {
                  $item['content'] .=  $CFG_GLPI['root_doc']."/pics/timeline/file.png";
               }
               $item['content'] .=  "'/>&nbsp;";

               $docsrc = $CFG_GLPI['root_doc']."/front/document.send.php?docid=".$item['id']
                      ."&tickets_id=".$kanban_curr_item_id;
                      $item['content'] .= Html::link($filename, $docsrc, ['target' => '_blank']);
               $docpath = GLPI_DOC_DIR . '/' . $item['filepath'];
               if (Document::isImage($docpath)) {
                  $imgsize = getimagesize($docpath);
                  $item['content'] = Html::imageGallery([
                     [
                        'src'             => $docsrc,
                        'thumbnail_src'   => $docsrc . '&context=timeline',
                        'w'               => $imgsize[0],
                        'h'               => $imgsize[1]
                     ]
                  ], [
                     'gallery_item_class' => 'timeline_img_preview'
                  ]);
               }
            }
            if ($item['link']) {
               $item['content'] .= "<a href='{$item['link']}' target='_blank'><i class='fa fa-external-link'></i>{$item['name']}</a>";
            }

         } else {
            $item['content'] = htmlspecialchars_decode($item['content']);
         }

         $user = new User();
         $user->getFromDB($item['users_id']);

         if ($user->fields["firstname"] && !$user->fields["realname"]) {
            $user->fields["realname"] = $user->fields["firstname"];
            $user->fields["firstname"] = '';
         }

         $item['firstname'] = formatUserName(
            $user->fields["id"],
            $user->fields["name"],
            $user->fields["realname"],
            $user->fields["firstname"]
         );

         return $item;
      }, $timeline);

      return array_values(array_reverse($timeline));
   }

   public function getKanbanBoards(){
      $query = 'select ti.id, ti.priority as border  ,CONCAT( "Nº ",ti.id," - ",ti.name ) as title, ti.status,DATE_FORMAT(ti.date_mod,"%d " "%b" )as date,us.firstname as user_last_updater, us_r.firstname as user_recipient, COUNT(*) as qt_acompanhamento from glpi_tickets ti left join glpi_users as us on ti.users_id_lastupdater = us.id left join glpi_users as us_r on ti.users_id_recipient = us_r.id left  join glpi_itilfollowups as ac on ti.id = ac.items_id where ti.status in (1,2,3,4,5,6) GROUP BY ti.id order by ti.status asc';
      
      $boards = array();
      $campos = array();
      if ($result = $this->conection->query($query)) {          
          $espera = array();
          $espera['id'] = '1';
          $espera['title'] = 'Novo';
          $espera['border'] = "success";
          $espera['total'] = 0;

          $atendimento = array();
          $atendimento['id'] = '2';
          $atendimento['title'] = 'Processando(Atribuido)';
          $atendimento['border'] = "success";
          $atendimento['badgeColor']= "danger";
          $atendimento['total'] = 0;

          $atendimentoProc = array();
          $atendimentoProc['id'] = '3';
          $atendimentoProc['title'] = 'Processando(Planejado)';
          $atendimentoProc['border'] = "success";
          $atendimentoProc['badgeColor']= "danger";
          $atendimentoProc['total'] = 0;

          $pendente = array();
          $pendente['id'] = '4';
          $pendente['title'] = 'Pendente';
          $pendente['total'] = 0;

          $atendido = array();
          $atendido['id'] = '5';
          $atendido['title'] = 'Solucionado';
          $atendido['total'] = 0;

          $fechado = array();
          $fechado['id'] = '6';
          $fechado['title'] = 'Fechado';
          $fechado['total'] = 0;
          while ($boards = $this->conection->fetchAssoc($result)) {
              //echo '<pre>'; var_dump($boards);
              $boards['content']= htmlspecialchars_decode($boards['content']);
              switch($boards['status']){
                  case 1:
                     $espera['total'] = $espera['total'] + 1;   
                     $espera['item'][] = $boards;
                  break;
                  
                  case 2:       
                     $atendimento['total'] = $atendimento['total'] + 1;  
                     $atendimento['item'][] = $boards;
                  break;
                  case 3:       
                     $atendimentoProc['total'] = $atendimento['total'] + 1;  
                     $atendimentoProc['item'][] = $boards;
                  break;

                  case 4:                     
                     $pendente['total'] = $pendente['total'] + 1;   
                     $pendente['item'][] = $boards;
                  break;

                  case 5:  
                     $atendido['total'] = $atendido['total'] + 1;                 
                     $atendido['item'][] = $boards;
                  break;
                  
                  case 6:
                     $fechado['total'] = $fechado['total'] + 1;
                     $fechado['item'][] = $boards;
                  break;
              }              
          }          
          
          $res = array($espera, $atendimento,$atendimentoProc, $pendente, $atendido, $fechado);
          return $res;          
      }
   }

   public function getKanbanBoardsFilter($filtroAbertoPorMim,$filtroAtribuidoParaMim,$filtroAtribuidoParaMinhaEquipe,$filtroLocal,$filtroTipo,$filtroCategoria,$dataInicioAbertura,$dataFinalAbertura){
      $query = self::getQueryFilter($filtroAbertoPorMim,$filtroAtribuidoParaMim,$filtroAtribuidoParaMinhaEquipe,$filtroLocal,$filtroTipo,$filtroCategoria,$dataInicioAbertura,$dataFinalAbertura);
      
      $boards = array();
      $campos = array();
      if ($result = $this->conection->query($query)) {          
          $espera = array();
          $espera['id'] = '1';
          $espera['title'] = 'Novo';
          $espera['border'] = "success";
          $espera['total'] = 0;

          $atendimento = array();
          $atendimento['id'] = '2';
          $atendimento['title'] = 'Processando(Atribuido)';
          $atendimento['border'] = "success";
          $atendimento['badgeColor']= "danger";
          $atendimento['total'] = 0;

          $atendimentoProc = array();
          $atendimentoProc['id'] = '3';
          $atendimentoProc['title'] = 'Processando(Planejado)';
          $atendimentoProc['border'] = "success";
          $atendimentoProc['badgeColor']= "danger";
          $atendimentoProc['total'] = 0;

          $pendente = array();
          $pendente['id'] = '4';
          $pendente['title'] = 'Pendente';
          $pendente['total'] = 0;

          $atendido = array();
          $atendido['id'] = '5';
          $atendido['title'] = 'Solucionado';
          $atendido['total'] = 0;

          $fechado = array();
          $fechado['id'] = '6';
          $fechado['title'] = 'Fechado';
          $fechado['total'] = 0;
          while ($boards = $this->conection->fetchAssoc($result)) {
              //echo '<pre>'; var_dump($boards);
              $boards['content']= htmlspecialchars_decode($boards['content']);
              switch($boards['status']){
                  case 1:
                     $espera['total'] = $espera['total'] + 1;   
                     $espera['item'][] = $boards;
                  break;
                  
                  case 2:       
                     $atendimento['total'] = $atendimento['total'] + 1;  
                     $atendimento['item'][] = $boards;
                  break;
                  case 3:       
                     $atendimentoProc['total'] = $atendimento['total'] + 1;  
                     $atendimentoProc['item'][] = $boards;
                  break;

                  case 4:                     
                     $pendente['total'] = $pendente['total'] + 1;   
                     $pendente['item'][] = $boards;
                  break;

                  case 5:  
                     $atendido['total'] = $atendido['total'] + 1;                 
                     $atendido['item'][] = $boards;
                  break;
                  
                  case 6:
                     $fechado['total'] = $fechado['total'] + 1;
                     $fechado['item'][] = $boards;
                  break;
              }              
          }          
          
          $res = array($espera, $atendimento,$atendimentoProc, $pendente, $atendido, $fechado);
          return $res;          
      }


   }

   public function getQueryFilter($filtroAbertoPorMim,$filtroAtribuidoParaMim,$filtroAtribuidoParaMinhaEquipe,$filtroLocal,$filtroTipo,$filtroCategoria,$dataInicioAbertura,$dataFinalAbertura){

      $campos = 'select ti.id, ti.priority as border  ,CONCAT( "Nº ",ti.id," - ",ti.name ) as title, ti.status,DATE_FORMAT(ti.date_mod,"%d " "%b" )as date,us.firstname as user_last_updater, us_r.firstname as user_recipient, COUNT(*) as qt_acompanhamento from ';
     
      $join = " glpi_tickets ti left join glpi_users as us on ti.users_id_lastupdater = us.id left join glpi_users as us_r on ti.users_id_recipient = us_r.id left  join glpi_itilfollowups as ac on ti.id = ac.items_id ";
    
      $joinFiltroAtribuidoParaMim = "";
      $joinFiltroAtribuidoParaMinhaEquipe = "";
      if($filtroAtribuidoParaMim == "true") { 
         $joinFiltroAtribuidoParaMim = "  INNER JOIN glpi_tickets_users tu on ti.id=tu.tickets_id and tu.type=2 ";
      }if($filtroAtribuidoParaMinhaEquipe == "true"){
         $joinFiltroAtribuidoParaMinhaEquipe = " INNER JOIN glpi_groups_tickets gt on ti.id=gt.tickets_id and gt.type=2 ";
      }
      $where = "  where ti.status in (1,2,3,4,5,6)  and ";
      $filtros = self::obterFiltro($filtroAbertoPorMim,$filtroAtribuidoParaMim,$filtroAtribuidoParaMinhaEquipe,$filtroLocal,$filtroTipo,$filtroCategoria,$dataInicioAbertura,$dataFinalAbertura);
      
    
      $query = $campos . $join . $joinFiltroAtribuidoParaMim . $joinFiltroAtribuidoParaMinhaEquipe . $where .  $filtros .  ' GROUP BY ti.id order by ti.status asc';

      return  $query;
   }

   public function obterFiltro($filtroAbertoPorMim,$filtroAtribuidoParaMim,$filtroAtribuidoParaMinhaEquipe,$filtroLocal,$filtroTipo,$filtroCategoria,$dataInicioAbertura,$dataFinalAbertura){
         $and = " and ";
         $where = "" ;
         $whereFiltroAbertoPorMim = "";
         $whereFiltroAtribuidoParaMim = "";
         $whereFiltroAtribuidoParaMinhaEquipe = "";
         $whereFiltroLocal = "";
         $whereFiltroTipo = "";
         $whereFiltroCategoria = "";
         $whereDataInicioAbertura = "";
         $meu_userId =$_SESSION['glpiID']; ;
         if($filtroAbertoPorMim == "true"){
            $whereFiltroAbertoPorMim = " ti.users_id_recipient = $meu_userId  "  ; 
         }      
         if($filtroAtribuidoParaMim == "true"){
            $whereFiltroAtribuidoParaMim = " tu.users_id = $meu_userId ";

         }if($filtroAtribuidoParaMinhaEquipe == "true"){
            $whereFiltroAtribuidoParaMinhaEquipe = " gt.groups_id in (select groups_id from glpi_groups_users where users_id = $meu_userId) ";
         }
         if(count($filtroLocal) > 0){
            if($filtroLocal[0] != "0"){
               $whereFiltroLocal = " locations_id in ( " . self::formatArrayToString($filtroLocal). ")";   
            }
         }if(count($filtroTipo) > 0){
            if($filtroTipo[0] != "0"){
               $whereFiltroTipo = " ti.type in ( " . self::formatArrayToString($filtroTipo). ")";
            }
         }if(count($filtroCategoria) > 0){
            if($filtroCategoria[0] != "0"){
                $whereFiltroCategoria = " ti.itilcategories_id in ( " . self::formatArrayToString($filtroCategoria). ")";
            }    

         }if($dataInicioAbertura != ""){

            $dataInicio = self::formatDate($dataInicioAbertura);
            $dataFim = self::formatDate($dataFinalAbertura);
            $whereDataInicioAbertura = " ti.date_creation between '$dataInicio' and '$dataFim' ";

         }
         $where = $whereFiltroAbertoPorMim; 

         if($whereFiltroAtribuidoParaMim != ""){
            if($where != ""){
                $where = $where . $and . $whereFiltroAtribuidoParaMim ;    
            }else{ 
               $where =  $whereFiltroAtribuidoParaMim ;  
            }    
         }


         if($whereFiltroAtribuidoParaMinhaEquipe != ""){ 
               if($where != ""){
                  $where = $where . $and .  $whereFiltroAtribuidoParaMinhaEquipe;
               }else{
                  $where =  $whereFiltroAtribuidoParaMinhaEquipe;
               }
          }
          
          if($whereFiltroLocal != ""){ 
            if($where != ""){
               $where = $where . $and .  $whereFiltroLocal;
            }else{
               $where =  $whereFiltroLocal;
            }
          }   

          if($whereFiltroTipo != ""){ 
            if($where != ""){
               $where = $where . $and .  $whereFiltroTipo;
            }else{
               $where =  $whereFiltroTipo;
            }
          }  

          if($whereFiltroCategoria != ""){ 
            if($where != ""){
               $where = $where . $and .  $whereFiltroCategoria;
            }else{
               $where =  $whereFiltroCategoria;
            }
          } 
          
          if($whereDataInicioAbertura != ""){ 
            if($where != ""){
               $where = $where . $and .  $whereDataInicioAbertura;
            }else{
               $where =  $whereDataInicioAbertura;
            }
          } 
         return $where;
   }

   public function formatDate($date){
      return  date('Y-m-d',strtotime(str_replace('/', '-', $date)));
   }

   public function  formatArrayToString($array){
               
         $string = "";

         foreach ($array as &$value) {
           if($string == ""){
                $string=  $value .","  ;
           }else{
               $string= $string . $value .","  ;
            }
         }

           return substr( $string,0,-1); 
   }


}