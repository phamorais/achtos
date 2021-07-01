<?php


class Search {
    //recebe um array de String e uma frase String, monta um select para realizar uma consulta
    public function MontarQueryForms($listStr,$frase) {

        $assunto = null;
        $assuntoForm = null;

        $i=1;
        foreach ($listStr as $str) {
            if(strlen($str) >= 2){
                if($assunto == null) {
                    $assuntoForm = "'%$str%'";
                    $assunto = "'%$str%'";
                    $i++;
                }elseif ($assunto != null && $i < 2) {
                    $assunto .= " OR glpi_plugin_formcreator_questions.description LIKE '%$str%'";
                    $assuntoForm .= " OR glpi_plugin_formcreator_forms.name LIKE '%$str%' ";
                    $i++;
                }/*AND IF*/
            }/*AND IF*/
        }/*AND FOREACH*/


        $result_msg_cont = "SELECT DISTINCT
        glpi_plugin_formcreator_forms.id AS idForm,
        glpi_plugin_formcreator_forms.name AS nomeForm,
        glpi_plugin_formcreator_forms.access_rights AS acesso,
        glpi_plugin_formcreator_categories.name AS nomeCategoria,
        glpi_plugin_formcreator_forms.is_active AS status,
        glpi_plugin_formcreator_forms.is_deleted
        FROM glpi_plugin_formcreator_questions
        INNER JOIN glpi_plugin_formcreator_sections
        ON glpi_plugin_formcreator_questions.plugin_formcreator_sections_id = glpi_plugin_formcreator_sections.id
        INNER JOIN glpi_plugin_formcreator_forms
        ON glpi_plugin_formcreator_sections.plugin_formcreator_forms_id = glpi_plugin_formcreator_forms.id
        INNER JOIN glpi_plugin_formcreator_categories
        ON glpi_plugin_formcreator_forms.plugin_formcreator_categories_id = glpi_plugin_formcreator_categories.id
        WHERE( glpi_plugin_formcreator_forms.name LIKE '%".$frase."%'
        OR glpi_plugin_formcreator_forms.name LIKE ".$assuntoForm." 
        OR glpi_plugin_formcreator_questions.description LIKE '%".$frase."%'
        OR glpi_plugin_formcreator_questions.description LIKE ".$assunto.")
        AND glpi_plugin_formcreator_forms.is_deleted = 0
        AND glpi_plugin_formcreator_forms.is_active = 1
         LIMIT 16";

        return  $result_msg_cont ;

    }
    //recebe frase String e separa cada palavra em um uma posição em um Array
    public function MontarListStr($assunto) {

        $array = explode(" ", $assunto);
        $listStr = array();

        $i = 0;
        foreach ($array as $str){
            if($str != null){
                $listStr[$i] = $str;
                $i++;
            }
        }
        return $listStr;
    }

    //Select "verificação de primissão de acesso do ususario au form"
    public function ValidarPermissaoForm($profile_id,$IDForm){
        return "SELECT
        glpi_plugin_formcreator_forms.id AS IDForm,
        glpi_plugin_formcreator_forms_profiles.profiles_id AS Profiles,
        glpi_plugin_formcreator_forms.name
        FROM glpi_plugin_formcreator_forms
        INNER JOIN glpi_plugin_formcreator_forms_profiles
        ON glpi_plugin_formcreator_forms.id = glpi_plugin_formcreator_forms_profiles.plugin_formcreator_forms_id
        WHERE glpi_plugin_formcreator_forms_profiles.profiles_id = $profile_id
        AND glpi_plugin_formcreator_forms.id = $IDForm
        AND glpi_plugin_formcreator_forms.is_deleted = 0
        AND glpi_plugin_formcreator_forms.is_active = 1";
    }

    public  function RetornaIdForm($assunto){

        $i = 0;
        foreach ($_SESSION['listaSearch'] as $array){

            $result = array_search($assunto, $_SESSION['listaSearch'][$i]);

            if($result){
                $IDForm = $array['idForm'];
                break;
            }
            $i++;
        }/*END FOREACH*/
        return $IDForm;
    }

}
