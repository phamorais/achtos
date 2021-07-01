<?php
include_once('../../../inc/includes.php');


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();

include_once(GLPI_ROOT . "/inc/based_config.php");
include_once(GLPI_ROOT . "/inc/define.php");
include_once(GLPI_ROOT . "/inc/dbconnection.class.php");

class TicketBoard
{
    const MELHORAR = 'melhorar';
    const MANTER = 'manter';

    public static function getTickets($query = null, $ticketId = 0, $flag = 0)
    {



        global $DB;
        $tickets = new Ticket();
        $userId =  $_SESSION['glpiID'];



    $query_forms = "select glpi_tickets.id, 
                        glpi_tickets.name, glpi_tickets.content, glpi_tickets.id, glpi_tickets.status , glpi_tickets.closedate
                        from glpi_tickets_users 
                        inner join glpi_tickets on glpi_tickets.id = glpi_tickets_users.tickets_id 
                        inner join glpi_itilcategories on glpi_tickets.itilcategories_id = glpi_itilcategories.id
                        where glpi_tickets_users.users_id =  $userId and glpi_tickets_users.type in(1,3)    
                        ";    

        if ($query) {
            if ($query == 1) {
                $where = self::MELHORAR;
            } elseif ($query == 2) {
                $where = self::MANTER;
            }            
            $query_forms = $query_forms . " AND lower(glpi_itilcategories.completename) LIKE lower('$where%')";
        }

        if ($ticketId && $flag == 1) {
            $query_forms = $query_forms . " AND  glpi_tickets.id = '$ticketId' ";
        }elseif($ticketId && $flag == 2){
            $query_forms = $query_forms . " AND  glpi_tickets.name like '%$ticketId%' ";
        }       
        
        $query_forms = $query_forms. " union select glpi_tickets.id, glpi_tickets.name, glpi_tickets.content, glpi_tickets.id, glpi_tickets.status,glpi_tickets.closedate  from glpi_tickets inner join glpi_itilcategories on glpi_tickets.itilcategories_id = glpi_itilcategories.id where users_id_recipient =  $userId"; 
        
        
      


           if ($query) {
            if ($query == 1) {
                $where = self::MELHORAR;
            } elseif ($query == 2) {
                $where = self::MANTER;
            }            
            $query_forms = $query_forms . " AND lower(glpi_itilcategories.completename) LIKE lower('$where%')";
        }

        if ($ticketId && $flag == 1) {
            $query_forms = $query_forms . " AND  glpi_tickets.id = '$ticketId' ";
        }elseif($ticketId && $flag == 2){
            $query_forms = $query_forms . " AND  glpi_tickets.name like '%$ticketId%' ";
        }       
        
        
        $result = $DB->query($query_forms);
        $ticketsResult=[];
        while ($tickets = $DB->fetchArray($result)) {
            $ticketsResult[] = $tickets;
        }
        
        $returnTickets['aguardando']['total']   = 0;
        $returnTickets['atendendo']['total']    = 0;
        $returnTickets['pendente']['total']     = 0;
        $returnTickets['validacao']['total']    = 0;

        foreach ($ticketsResult as $tick) {
            if ($tick['status'] == 1) {

                $returnTickets['aguardando'][] = $tick;
                $returnTickets['aguardando']['total']++;

            } else if ($tick['status'] == 2) {
                

                $iterator = $DB->request(['SELECT' => ['type'],
                                    'FROM'   => 'glpi_tickets_users',
                                    'WHERE'  => ['tickets_id' => $tick['id'], 'type' => 2 ]
                ]);
                
                $have = false ;

                while ($data = $iterator->next()) {     
                    $have =true;                    
                }
                if($have){
                    $returnTickets['atendendo'][] = $tick;
                    $returnTickets['atendendo']['total']++;
                }else{
                    $returnTickets['aguardando'][] = $tick;
                    $returnTickets['aguardando']['total']++;
                }                                

            }else if ($tick['status'] == 3) {

                $returnTickets['atendendo'][] = $tick;
                $returnTickets['atendendo']['total']++;

            } else  if ($tick['status'] == 4) {
            
                $returnTickets['pendente'][] = $tick;
                $returnTickets['pendente']['total']++;
            
            } else if ($tick['status'] == 5) {
            
                $returnTickets['validacao'][] = $tick;
                $returnTickets['validacao']['total']++;
            
            }else if($tick['status'] == 6){
                
                $datetime1 = new DateTime($tick['closedate']);
                $datetime2 = new DateTime("now");
                $interval = $datetime1->diff($datetime2);

                if($interval->days <= 90){
                    $returnTickets['fechado'][] = $tick;
                    $returnTickets['fechado']['total']++;
                }              
                
            }
        }        
        return $returnTickets;
    }


    public static function soluteTicket($post)
    {
        // new \QueryExpression("NOW()")
        global $DB;
        if(isset($_POST['add_close'])){
                
            $DB->insert('glpi_ticketsatisfactions', [
                'tickets_id'  => $post["tickets_id"],
                'type'        => 1,
                'satisfaction'=> $post["satisfaction"],                        
                'comment'     => $post['content'],
                'date_begin'  =>new \QueryExpression("NOW()"),
                'date_answered'  => new \QueryExpression("NOW()")
            ]);

            $DB->update('glpi_tickets', ['status' => 6,'satisfaction'=> $post["satisfaction"], 'closedate'=>new \QueryExpression("NOW()")], ['id' => $post["tickets_id"]]);

            $fup = new ITILFollowup();
            // @todo Habilitar verificação de permissão
            // $fup->check(-1, CREATE, $_POST);
    
            $fup->add([
                'itemtype'  => 'Ticket',
                'items_id'        => $post["tickets_id"],
                'users_id' => $_SESSION['glpiID'],
                'users_id_editor'=> 0,
                'content' => $post['content'],
                'is_private'=> 0,
                'requesttypes_id'=> 1,
                'timeline_position'=> 1,
            ]);
        }else if( isset($_POST['add_reopen']) ){

            $DB->update('glpi_tickets', ['status' => 2], ['id' => $post["tickets_id"]]);

            $fup = new ITILFollowup();
            // @todo Habilitar verificação de permissão
            // $fup->check(-1, CREATE, $_POST);

            $fup->add([
                'itemtype' => 'Ticket',
                'items_id' => $post["tickets_id"],
                'users_id' => $_SESSION['glpiID'],
                'users_id_editor'=> 0,
                'content' => $post['content'],
                'is_private'=> 0,
                'requesttypes_id'=> 1,
                'timeline_position'=> 1,
            ]);

              $DB->update('glpi_itilsolutions', ['status' => 4, 'date_approval'=>new \QueryExpression("NOW()")], ['items_id' => $post["tickets_id"]]);

        }
      
    }


    public static function setFollowup($post){

        $fup = new ITILFollowup();
        // @todo Habilitar verificação de permissão
        // $fup->check(-1, CREATE, $_POST);
        $fup->add([
            'itemtype'  => 'Ticket',
            'items_id'        => $post["tickets_id"],
            'users_id' => $_SESSION['glpiID'],
            'users_id_editor'=> 0,
            'content' => $post['content'],
            'is_private'=> $post['privado'],
            'requesttypes_id'=> 1,
            'timeline_position'=> 4,
        ]);
        return $post;

    }

    public static function setStatus($post) {
        if ($post['id_status'] == "5") {

            $solution = new ITILSolution();
            $solution->add([
                'itemtype' => 'Ticket',
                'items_id' => $post["tickets_id"],
                'solutiontemplates_id' => '0',
                'solutiontypes_id' => '0',
                'content' => $post["text_solution"],
                '_no_message_link' => '1',
                '_sol_to_kb' => '0',
            ]);
            return $post;
        }

        $track = new Ticket();
        $track->update([
            'id' => $post["tickets_id"],
            'status' => $post["id_status"],
        ]);

        return $post;
    }


    public static function getCategories(){

        global $DB;

        $criteria = [
            'SELECT'    => [
               'glpi_itilcategories.id',
               'glpi_itilcategories.completename  AS category'
            ],
            'DISTINCT'  => true,
            'FROM'      => 'glpi_itilcategories',
            'WHERE'     => getEntitiesRestrictCriteria('glpi_itilcategories', '', '', true),
            'ORDERBY'   => 'completename'
         ];

         $iterator = $DB->request($criteria);

         $val    = [];
         while ($line = $iterator->next()) {
            $val[] = [
               'id'     => $line['id'],
               'category'   => $line['category']
            ];
         }

         return  $val;
    }

    public static function getLocations(){

        global $DB;
        
        $criteria = [
            'SELECT'    => [
               'glpi_locations.id',
               'glpi_locations.completename  AS locations'
            ],
            'DISTINCT'  => true,
            'FROM'      => 'glpi_locations',
            'ORDERBY'   => 'completename'
         ];

         $iterator = $DB->request($criteria);

         $val    = [];
         while ($line = $iterator->next()) {
            $val[] = [
               'id'     => $line['id'],
               'locations'   => $line['locations']
            ];
         }

         return  $val;
    }
}