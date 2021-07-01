<?php


include_once(__DIR__ . "/validar.class.php");

class LastSearchForms{
  
    public function LastSearch($userID){

          // VARIÁVEL QUE EXECUTA A QUERY
          global $DB;

        $listForms= "SELECT
        glpi_plugin_front_laststickets.plugin_formcreator_forms_id AS IDForm,
        glpi_plugin_formcreator_forms.name
        FROM glpi_plugin_formcreator_forms
        INNER JOIN glpi_plugin_front_laststickets
        ON glpi_plugin_formcreator_forms.id = glpi_plugin_front_laststickets.plugin_formcreator_forms_id
        WHERE glpi_plugin_front_laststickets.user_id = $userID
        AND glpi_plugin_formcreator_forms.is_deleted = 0
        AND glpi_plugin_formcreator_forms.is_active = 1
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
            if( $resultado =  $DB->fetchArray($resultado)){
                
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
        
      
        if($counts = $DB->fetchArray($resultado)){
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


    public function ShowLinks($ids){
        global $DB;

        echo '<div class="minhas-pesquisas text-center color-light">';
        echo "Formulários mais utilizados : ";
        $i = 0;
        while ($row_msg_cont = $DB->fetchArray($ids)) {

        $IDForms[$i][0] = $row_msg_cont['IDForm'];
        $IDForms[$i][1] = $row_msg_cont['name'];
        echo "<a  class='minhas-pesquisas' href='../../formcreator/front/formdisplay.php?id= ".$row_msg_cont['IDForm']."' > ".$row_msg_cont['name']." </a>, ";

            
        $i++;
        } //end while
        echo '</div>';
    //     echo "<pre>";
    //    print_r($IDForms);

    }

}



    
      