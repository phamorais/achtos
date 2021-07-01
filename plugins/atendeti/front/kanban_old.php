
<?php


include "../../../inc/includes.php";

Session::checkLoginUser();

//Html::header(Ticket::getTypeName(Session::getPluralNumber()), '', "helpdesk");
Html::header(__('Kanban', 'helpdesk'), $_SERVER['PHP_SELF'], 'helpdesk', 'kanban');

//Search::show('Ticket');

?>

<?php

Html::footer();

?>