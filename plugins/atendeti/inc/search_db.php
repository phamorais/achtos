<?php
require('../../../inc/includes.php');

include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");
include_once("./search.class.php");

$assunto = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
$id_user = (Session::getLoginUserID());

$search = new Search();

$listStr = $search->MontarListStr($assunto);


if(ctype_digit($assunto)){// IS number ?

        $result_msg_cont = "SELECT glpi_tickets.id
        FROM glpi_tickets 
        WHERE glpi_tickets.id LIKE '%".$assunto."%' 
        AND  glpi_tickets.users_id_recipient = $id_user
        ORDER BY glpi_tickets.id ASC LIMIT 7";

        $resultado_msg_cont = $DB->query($result_msg_cont);

        while($row_msg_cont = $DB->fetchArray($resultado_msg_cont)){
            $data[] = $row_msg_cont['id'];       //name of the column in the table
        }

        echo json_encode($data);

}elseif ($listStr) {


    //Select Buscar forms
    $result_msg_cont = $search->MontarQueryForms($listStr,$assunto);


if($resultado_msg_cont = $DB->query($result_msg_cont)){


    $profile_id = $_SESSION['glpiactiveprofile']['id'];
    $list1 = array();
    $listaSearch = array();

    $i = 0;
    while($row_msg_cont = $DB->fetchArray($resultado_msg_cont)){

      if($row_msg_cont['acesso'] == 1 || $row_msg_cont['acesso'] == 0){


              $list1[$i] = $row_msg_cont['nomeForm'];
              $list1[$i] .= " - " . $row_msg_cont['nomeCategoria'] . "";
              $listaSearch[$i] = array(
                  'idForm' => $row_msg_cont['idForm'],
                  'nomeSearch' => $list1[$i]
              );
              $i++;

      }elseif ($row_msg_cont['acesso'] == 2) {
          
        $IDForm = $row_msg_cont['idForm'];


        //Select
          $forms = $search->ValidarPermissaoForm($profile_id,$IDForm);


         $is_privat = $DB->query($forms);
        
        if($is_privat){
         
          $is_privat  = $DB->fetchArray($is_privat);

          if($is_privat['Profiles'] == $profile_id ){


                  $list1[$i] = $row_msg_cont['nomeForm'];
                  $list1[$i] .= " - " . $row_msg_cont['nomeCategoria'] . "";
                  $listaSearch[$i] = array(
                      'idForm' => $row_msg_cont['idForm'],
                      'nomeSearch' => $list1[$i]
                  );
                  $i++;


          }/* END IF */
        }/* END IF */

      }/* END ELSEIF */ 
    }/* END WHILE */

  }/* END IF*/
    $_SESSION['listaSearch'] = $listaSearch;


    if($list1[0]){
        echo json_encode($list1);

    }else{
        $resul = ['Nenhum resultado encontrado'];
        echo json_encode($resul);
    }/*END IF*/
}//end elseif





  




