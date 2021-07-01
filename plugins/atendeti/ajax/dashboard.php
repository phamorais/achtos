<?php

include('../inc/dashboard.class.php');
include('../inc/rules.class.php');
Session::checkLoginUser();
/* Recupera os valores da requisição */
$rule_id = $_REQUEST['rule_id'];

if ($rule_id) {
    $dashboard = PluginFrontRules::updateRoleStatus($rule_id);
   
}
echo $tema;
