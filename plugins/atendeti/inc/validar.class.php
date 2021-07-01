<?php

class Validar
{



  public function validarForms($IDcategoria)
  {
    global $DB;

    //pega todos os forms da categoria!
    $form = "SELECT
    glpi_plugin_formcreator_forms.id AS IDForm,
     glpi_plugin_formcreator_forms.access_rights
    FROM glpi_plugin_formcreator_forms
    INNER JOIN glpi_plugin_formcreator_categories
    ON glpi_plugin_formcreator_forms.plugin_formcreator_categories_id = glpi_plugin_formcreator_categories.id
    WHERE glpi_plugin_formcreator_categories.id = $IDcategoria
    AND glpi_plugin_formcreator_forms.is_deleted = 0
    AND glpi_plugin_formcreator_forms.is_active = 1";


    $buscaForms = $DB->query($form);

    $i = 0;
    while ($row_msg_cont = $DB->fetchArray($buscaForms)) {

      $Form[$i][0] = $row_msg_cont['IDForm'];
      $Form[$i][1] = $row_msg_cont['access_rights'];


      $i++;
    } //end while



    for ($i = 0; $i < count($Form); $i++) {

      if ($Form[$i][1] == 1) { // publico :privado

        $resultForm[] = $Form[$i][0];
      } elseif ($Form[$i][1] == 2) { //restrito para perfil

        $IDForm = $Form[$i][0];
        $profile_id = $_SESSION['glpiactiveprofile']['id'];

        $forms = "SELECT
          glpi_plugin_formcreator_forms.id AS IDForm,
          glpi_plugin_formcreator_forms_profiles.profiles_id AS Profiles
          FROM glpi_plugin_formcreator_forms
          INNER JOIN glpi_plugin_formcreator_forms_profiles
          ON glpi_plugin_formcreator_forms.id = glpi_plugin_formcreator_forms_profiles.plugin_formcreator_forms_id
          WHERE glpi_plugin_formcreator_forms_profiles.profiles_id = $profile_id
          AND glpi_plugin_formcreator_forms.id = $IDForm
          AND glpi_plugin_formcreator_forms.is_deleted = 0
          AND glpi_plugin_formcreator_forms.is_active = 1";


        // $resultadoForms = $DB->query($forms);

        if ($resultadoForms = $DB->query($forms)) {

          $resultadoForms  = $DB->fetchArray($resultadoForms);


          if ($resultadoForms['Profiles'] == $profile_id) {

            $resultForm[] = $resultadoForms['IDForm'];
          }
        }
      } elseif ($Form[$i][1] == 0) { // publico todos

        $resultForm[] = $Form[$i][0];
      }
    }

    return $resultForm;
  }




  /*
  * MÉTODO QUE VALIDA O ACESSO DO USUÁRIO LOGADO AO FORM
  * COM PARÂMETRO
  *     FORM_ID
  * RETORNO
  *     RETORNA TRUE OU FALSE
  */
  public function validaForm($IDForm)
  {
    //Variável de acesso ao BD
    global $DB;

    //Tabela utilizadas
    $profile_id = $_SESSION['glpiactiveprofile']['id'];
    $profile_table = 'glpi_plugin_formcreator_forms_profiles';
    $form_table = 'glpi_plugin_formcreator_forms';

    //SELECT QUE TRAZ O FORM COM NIVEL DE ACESSO E SEU PERFIL CASO TENHA
    $query = "SELECT
                form.id,
                form.access_rights,
                profile.profiles_id
            FROM $form_table as form
            LEFT JOIN $profile_table as profile
            ON form.id = profile.plugin_formcreator_forms_id
            WHERE form.id = $IDForm";

    //EXECUTA A QUERY
    $result_validate_form = $DB->query($query);

    //ATRIBUI A UM ARRAY
    /* FAÇO ISSO PARA FACILITAR A MANIPULAÇÃO */
    while ($form = $DB->fetchArray($result_validate_form)) {
      $formValidate[] = $form;
    }
    foreach ($formValidate as $form) {

      //CASO O NÍVEL DE ACESSO DO FORM FOR 0 - PUBLIC OU 1 - PRIVADO
      if ($form['access_rights'] != 2) {

        return true;
      } else {

        //CASO O NÍVEL DE ACESSO DO FORM SEJA 2 - RESTRITO
        //VER SE O PERFIL LOGADO TEM ACESSO
        if ($form['profiles_id'] == $profile_id) {

          return true;
        }
      }
    }

    return false;
  }
}
