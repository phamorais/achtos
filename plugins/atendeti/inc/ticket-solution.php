<?php


include_once('ticket-board.class.php');


if (isset($_POST['add_close']) || isset($_POST['add_reopen'])) {
    TicketBoard::soluteTicket($_POST);
    Html::back();
}
