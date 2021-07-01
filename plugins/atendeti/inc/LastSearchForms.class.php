<?php

require('../../../inc/includes.php');

include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");
include_once("validar.class.php");

class LastSearchForms{
  
    public function LastSearch($userID){

        // VARIÁVEL QUE EXECUTA A QUERY
         global $DB;
        $profile_id = $_SESSION['glpiactiveprofile']['id'];
        $listForms= "SELECT
        glpi_plugin_front_laststickets.plugin_formcreator_forms_id AS IDForm,
        glpi_plugin_formcreator_forms.name,
        categ.name as categForm,
        categ.comment as categFormComment
        FROM glpi_plugin_formcreator_forms
        INNER JOIN glpi_plugin_formcreator_forms_profiles
            ON glpi_plugin_formcreator_forms.id = glpi_plugin_formcreator_forms_profiles.plugin_formcreator_forms_id
        INNER JOIN glpi_plugin_front_laststickets        
            ON glpi_plugin_formcreator_forms.id = glpi_plugin_front_laststickets.plugin_formcreator_forms_id
        INNER JOIN glpi_plugin_formcreator_categories as categ
            on categ.id = glpi_plugin_formcreator_forms.plugin_formcreator_categories_id
        WHERE glpi_plugin_front_laststickets.user_id = $userID
        AND glpi_plugin_formcreator_forms.is_deleted = 0
        AND glpi_plugin_formcreator_forms.is_active = 1
        AND glpi_plugin_formcreator_forms_profiles.profiles_id = $profile_id
        ORDER BY glpi_plugin_front_laststickets.count DESC
        LIMIT 4";

        $ids = $DB->query($listForms);

        return $ids;

    }/* LastSearch */

    public function hasLinks($IDForm,$UserID){

        global $DB;

        $query = "SELECT
                    glpi_plugin_front_laststickets.plugin_formcreator_forms_id
                    FROM glpi_plugin_front_laststickets
                    WHERE glpi_plugin_front_laststickets.user_id = $UserID
                    AND glpi_plugin_front_laststickets.plugin_formcreator_forms_id = $IDForm";

            $resultado = $DB->query($query);
            if( $resultado = $DB->fetchArray($resultado)){
                
                return true;

            }else{
               
                return false;
            }

    }/* END hasLinks */

    public function AddCount($IDForm,$count){

        global $DB;
        $UserID = $_SESSION['glpiID'];

        $query = "SELECT
        glpi_plugin_front_laststickets.count,
        glpi_plugin_front_laststickets.id
        FROM glpi_plugin_front_laststickets
        WHERE glpi_plugin_front_laststickets.user_id = $UserID
        AND glpi_plugin_front_laststickets.plugin_formcreator_forms_id = $IDForm";

        $resultado = $DB->query($query);

        if($counts =  $DB->fetchArray($resultado)){
        $qtdcount = $counts['count'];
        $id =  $counts['id'];
        }
        $num = $qtdcount+$count;
        $query = "UPDATE glpi_plugin_front_laststickets 
        SET glpi_plugin_front_laststickets.count = $num
        WHERE (glpi_plugin_front_laststickets.id = $id) 
        AND (glpi_plugin_front_laststickets.plugin_formcreator_forms_id = $IDForm ) 
        and (glpi_plugin_front_laststickets.user_id = $UserID)";


        $resultado = $DB->query($query);

    }/*  END  AddSearch */

    public function AddSearch($IDForm, $IDuser){

        global $DB;
        $query = "INSERT INTO glpi_plugin_front_laststickets
        (glpi_plugin_front_laststickets.plugin_formcreator_forms_id,
         glpi_plugin_front_laststickets.user_id, 
         glpi_plugin_front_laststickets.count) 
        VALUES ($IDForm, $IDuser, 1)";
       
        $resultado = $DB->query($query);

    }

 public static function ShowLinks($ids, $virgula = false){

        echo '<style>
         div.minhas-pesquisas{
            font-size: 13px;
            font-weight: bold;
         }        

         a.minhas-pesquisas{
          padding: 10px;
    background-color: #336099;
    color: #FFFFFF;
    border-radius: 10px;
    margin-right: 10px;
    margin-top: 6px;
    border: #FFFFFF;
    border-style: solid;
    border-width: 1px;
    display: inline-block;            
         }

         a.minhas-pesquisas u{
               text-decoration: none!important;
         }
        </style>';

        
        echo '<div class="minhas-pesquisas text-center color-light">';
        echo "Frequentes : ";
        $i = 0;
        while ($row_msg_cont = $ids->fetch_array()) {

        $IDForms[$i][0] = $row_msg_cont['IDForm'];
        $IDForms[$i][1] = $row_msg_cont['name'];
        $IDForms[$i][2] = $row_msg_cont['categForm'];

        if($virgula){
            if($i > 0){ 
                echo "<a  class='minhas-pesquisas' href='../../formcreator/front/formdisplay.php?id= ".$row_msg_cont['IDForm']."' ><u>, " .$row_msg_cont['name'].'-'.$row_msg_cont['categForm']."</u> </a> ";
            }else{
                echo "<a  class='minhas-pesquisas' href='../../formcreator/front/formdisplay.php?id= ".$row_msg_cont['IDForm']."' ><u>" .$row_msg_cont['name'].'-'.$row_msg_cont['categForm']."</u> </a> ";        
            }
        }else{
            echo "<a  class='minhas-pesquisas' href='../../formcreator/front/formdisplay.php?id= ".$row_msg_cont['IDForm']."' ><u>" .$row_msg_cont['name'].' - '.$row_msg_cont['categForm']."</u> </a> ";
        }

        
        $i++;
        } //end while
        echo '</div>';
    }

}



    
      