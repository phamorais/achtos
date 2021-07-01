<?php

include 'header.php';


$ticketsPendente = TicketPendente::selectTicketPendente();

if ($ticketsPendente) {
    ?>
    <div class="container" style="height: 100vh">
        <div class="card" style="border: 1px solid #FFFFFF;background: white;border-radius: 0px !important;">
            <div class="card-head text-center">
                <h4><b>Aprovações pendentes</b></h4>

            </div>
            <div class="card-body">
                <div class="container">

                    <table class="table" id="tabela-aprovacao">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Solicitante</th>
                                <th>Mensagem</th>
                                <th class="text-right"></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                                foreach ($ticketsPendente as $key => $ticket) {
                                    ?>
                                <tr>
                                    <td class="link_table"><a href="<?= $CFG_GLPI["root_doc"] ?>/front/ticket.form.php?id=<?= $ticket['id'] ?>"><?= $ticket['id'] ?></a></td>
                                    <td>
                                        <span class="tooltiptext" rel="tooltip" data-toggle="tooltip" data-html="true" title=""> <?= $ticket['ticket_name'] ?>
                                        </span>
                                    </td>
                                    <td><?= $ticket['realname'] ?></td>
                                    
                                    <td class="link_table"><?= html_entity_decode($ticket['comment_submission']); ?> </td>


                                    <td class="td-actions text-right">
                                        <form action="<?= $CFG_GLPI["root_doc"] ?>/front/ticketvalidation.form.php" method="POST" id="form_<?= $key ?>">
                                            <?= Html::hidden('_glpi_csrf_token', ['value' => Session::getNewCSRFToken()]); ?>
                                            <?= Html::hidden('id', ['value' => $ticket['id_validation']]); ?>
                                            <textarea id="comment_validation" name="comment_validation" style="display: none"></textarea>
                                            <input type="hidden" name="update" value="Salvar">
                                            <input type="hidden" name="comment_submission" value='<?= html_entity_decode($ticket['comment_submission'])?>'>
                                            <button type="submit" name="status" value="3" class="btn btn-sm btn-light" title="Aprovar">
                                                <i class="fa fa-thumbs-o-up" aria-hidden="true" style="color:#4caf50"></i>
                                            </button>
                                            <button type="reset" name="status" value="4" class="btn btn-sm btn-light negar" data-toggle="modal" data-target="#modalMessage" title="Reprovar">
                                                <i class="fa fa-thumbs-o-down" aria-hidden="true" style="color:#f44336"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>


                        <?php
                            }
                        } else {
                            echo '<div class="card text-center"><h4> Nenhum chamado aguardando aprovação !</h4></div>';
                        }
                        ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="modalMessage" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><b>Por favor, informe o motivo da recusa</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalMessageBody">
                        <input type="hidden" class="hidden">
                        <textarea name="comment_validation" id="expire_comment_validation" class="col-12" placeholder="Escreva sua mensagem aqui"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class=" enviarNegar btn btn-primary">Enviar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include 'footer.php';
        ?>
            

        <script>

            jQuery(function($) {   if(window.screen.availWidth<500){ $('#tabela-aprovacao').addClass('table-responsive') } });

            $('.negar').on('click', function() {
                $('#modalMessage').children().children().children('#modalMessageBody').children('input.hidden').val($(this).parent().attr('id'));
            });

            $('.enviarNegar').on('click', function() {
                let id = $('#modalMessageBody').children('input.hidden').val();
                $('#' + id).children('textarea#comment_validation').val($('#modalMessageBody').children('textarea#expire_comment_validation').val());
                $('<input>').attr({
                    type: 'hidden',
                    name: 'status',
                    value: 4
                }).appendTo($('#' + id));
                $('#' + id).submit();
            });
        </script>