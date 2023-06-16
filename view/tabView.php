<?php
$menu = new menu();
$menu->menuGeneral();

?>

<hr />
<div class='row justify-content-between'>
    <div class='col-s8 ml-3 mt-1'>
        <h4 style="color: #162441;">Vista General Tableros</h4>
    </div>
    <!--<div class='col-s4 mr-3'>
        <button type="button" class="btn btn-flotante-degrade rounded-circle" data-toggle="modal" data-target="#modalAgregarAccesos"><i class="material-icons align-middle">add</i></button>
    </div>-->
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <table id="registros" class="table table-datatable table-sm table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Tablero</th>
                    <th>Email</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($tabersos) {
                    foreach ($tabersos as $reg) {?>
                        <tr>

                            <td><?php echo $reg["cliente_tablero"]; ?></td>
                            <td><?php echo $reg["nombre_tablero"]; ?></td>
                            <td><?php echo $reg["email_usuario"]; ?></td>
                            <td><?php echo $reg["nombre_rol"]; ?></td></tr>     
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<!-- Modales -->
<div class="modal fade" id="modalAgregarAccesos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevos Accesos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_accesos">
                    <div id="lista_personas">
                        <div class="form-group">
                            <label for="grupo_tableros">Grupo de tableros</label>
                            <select class="form-control" id="grupo_tableros" name="grupo_tableros" required multiple data-selected-text-format="count" data-actions-box="true">
                                <?php
                                foreach ($grupos as $grupo) {
                                    echo "<option value=" . $grupo->id_gt . ">" . $grupo->nombre_grupo . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label>Personas</label>
                        <div class="form-group">
                            <input type="email" class="form-control" name="form_personas" placeholder="Correo" required>
                            <button type="button" class="btn btn-sm text-success btn-add-quit" onclick="agregarItem()"><span class="material-icons">add_box</span></button>
                            <button type="button" class="btn btn-sm text-danger btn-add-quit" onclick="eliminarItem(this)"><span class="material-icons">remove_circle_outline</span></button>
                        </div>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Guardar">
                    <div id="spinner" style="display: none; margin-left: 10px;">
                        <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap-select CDN JS LINK -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.12/dist/js/bootstrap-select.min.js"></script>
<script src="assets/DataTables/dataTables.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {

        $('#registros').DataTable({
            dom: 'lBfrtip',
            buttons: ['excelHtml5'],
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
            }
        });

        $('#grupo_tableros').selectpicker();

        $('#form_accesos').on('submit', function(e) {

            e.preventDefault();
            var action = "index.php?controller=usuario&action=guardarAccesos";
            var grupos_tableros = $('#grupo_tableros').val();
            var emails = $('#form_accesos input[name="form_personas"]').serializeArray();
            
            $.ajax({
                beforeSend: function() {
                    $('#spinner').css("display", "inline-block");
                },
                url: action,
                type: "post",
                data: {
                    grupos_tableros: grupos_tableros,
                    emails: emails
                },
                success: function(resp) {
                    //console.log(resp);
                    var response = JSON.parse(resp);
                    $('#spinner').css("display", "none");
                    if (response == 'exito') {
                        alert("Registros guardados correctamente");
                        location.reload();
                    } else {
                        alert("Ha ocurrido un error al guardar");
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    console.log('AJAX Error - ' + errorMessage);
                }
            });
        });

    });

    function agregarItem() {
        html = "<div class='form-group'><input type='email' class='form-control' name='form_personas' placeholder='Correo' required><button type='button' class='btn btn-sm text-success btn-add-quit' onclick='agregarItem()'><span class='material-icons'>add_box</span></button><button type='button' class='btn btn-sm text-danger btn-add-quit' onclick='eliminarItem(this)'><span class='material-icons'>remove_circle_outline</span></button></div>";
        $('#lista_personas').append(html);
    }

    function eliminarItem(elem) {
        $(elem).parent().remove();
    }

    function eliminarRegistro(elem) {

        var resp = confirm("¿Está seguro de eliminar este registro?");
        if (!resp) {
            return false;
        }

        var action = "index.php?controller=usuario&action=eliminarAccesos";
        var id_usu = $(elem).data('id_usu');
        var id_grupo = $(elem).data('id_grupo');
        var email_usu = $(elem).data('email_usu');
        //alert(email_usu);

        $.ajax({
            url: action,
            type: "POST",
            data: {
                id_usu: id_usu,
                id_grupo: id_grupo,
                "email": email_usu
               
            },
            success: function(resp) {
                console.log(resp);
                var response = JSON.parse(resp);
                console.log(response);
                if (response == 'exito') {
                    alert("Registro eliminado correctamente");

                    location.reload();
                } else {
                    alert("Ha ocurrido un error al eliminar");
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log('AJAX Error - ' + errorMessage);
            }
        });
    }
</script>

</body>

</html>