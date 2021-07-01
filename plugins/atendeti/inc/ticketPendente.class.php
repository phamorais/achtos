<?php

require('../../../inc/includes.php');


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();
include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");


class TicketPendente
{




    public static function selectTicketPendente()
    {

        global $DB;
        $table = "glpi_ticketvalidations";

        $query_ticket_pendentes = " SELECT glpi_tickets.id,
                                        glpi_tickets.name as ticket_name,
                                        glpi_tickets.content,
                                        glpi_tickets.date_creation,
                                        solicitador.realname, 
                                        ticketPendente.comment_submission,
                                        ticketPendente.comment_validation,
                                        ticketPendente.id as id_validation  
                                    FROM  " . $table . " AS ticketPendente 
                                    INNER JOIN glpi_tickets ON ticketPendente.tickets_id = glpi_tickets.id
                                    INNER JOIN glpi_users as solicitador ON glpi_tickets.users_id_recipient = solicitador.id 
                                    WHERE ticketPendente.users_id_validate = " . $_SESSION['glpiID'] . " AND ticketPendente.status = 2 ";
//        echo '<br>'; print_r($query_ticket_pendentes);die;
        $result_tickets = $DB->query($query_ticket_pendentes);

        if (!mysqli_num_rows($result_tickets)) {

            return false;

        } else {

            while ($ticketPendentes = $DB->fetchArray($result_tickets)) {
                $tickets[] = $ticketPendentes;
            }

            return $tickets;
        }
    }

    public static function checkTicketPendente(){

        global $DB;
        $table = "glpi_ticketvalidations";

        $query_ticket_pendentes = " SELECT COUNT(ticketPendente.id)                                      
                                    FROM  " . $table . " AS ticketPendente
                                    WHERE ticketPendente.users_id_validate = " . $_SESSION['glpiID'] . " AND ticketPendente.status = 2 ";
        $result_tickets = $DB->query($query_ticket_pendentes);

            while ($ticketPendentes = $DB->fetchArray($result_tickets)) {

                $tickets = $ticketPendentes[0];
            }
            return $tickets;
    }


}


?>