<?php

include "../../inc/includes.php";

// if (!Session::getLoginUserID()) {
//   //Html::redirect("/glpi-poupex");
//   Html::redirectToLogin();
// }

Session::checkLoginUser();

Html::redirect($CFG_GLPI['root_doc'] . "/plugins/atendeti/front/home.php?frontsearch=".$_GET['frontsearch']);