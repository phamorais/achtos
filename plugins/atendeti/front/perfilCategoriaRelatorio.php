<?php include 'dashboard-header.php';
    $data = Dashboard::getAllCategoryProfile();
   
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-info">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <h4 class="card-title ">Pefil X Categoria</h4>                       
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="example">
                                <thead class=" text-primary">
                                <tr>
                                    <th width="">
                                        Perfil
                                    </th>
                                    <th>
                                        Categoria
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($data as $dt) {
                                    ?>
                                <?php foreach( $dt['categorias'] as $categoria){ ?>
                                    <tr>
                                        <td>
                                            <?= $dt['perfil']; ?>
                                        </td>
                                        <td>
                                            <?=$categoria?>
                                        </td>
                                    </tr>
                                <?php }?>                                     
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
<?php include 'dashboard-footer.php'; ?>
<script>
    $(document).ready(function() {
    $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [ {
                extend: 'csvHtml5',
                title: 'Perfil por categoria'
            },
            {
                extend: 'pdfHtml5',
                title: 'Perfil por categoria'
            }
        ],
        "language": {"url": "http://cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"}
    } );
} );</script>