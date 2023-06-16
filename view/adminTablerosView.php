<?php
$menu = new menu();
$menu->menuGeneral();

?>

<hr />
<div class='row'>
    <div class='col-s12 ml-3 mt-1'>
        <h4 style="color: #162441;">Tableros</h4>
    </div>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="registros" class="table table-sm table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Área de Trabajo</th>
                        <th>Fecha Publicación</th>
                        <th>Nombre Tablero en BI</th>
                        <th>Actualización Autom.</th>
                        <th>Imagen</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($allTableros) {
                        $btn = '<button type="button" class="btn btn-link">Link</button>';
                        foreach ($allTableros as $reg) {
                            $estado = ($reg->estado_tablero == 1) ? "Activo" : "Inactivo";
                            $actualizacion = ($reg->actualizacion_automatica == 1) ? "Sí" : "No";
                            $imagen = ($reg->imagen_tablero != "No") ? '<a href="assets/img/' . $reg->imagen_tablero . '.png" target="_blank">Ver</a>' : "No se encuentra";
                    ?>
                            <tr>
                                <td><?php echo $reg->nombre_grupo; ?></td>
                                <td><?php echo $reg->fecha_publicación; ?></td>
                                <td><?php echo $reg->nombre_tablero; ?></td>
                                <td><?php echo $actualizacion; ?></td>
                                <td><?php echo $imagen; ?></td>
                                <td><?php echo $estado; ?></td>
                                <td class="p-0">
                                    <button type="button" class="btn btn-sm text-dark p-0" data-nombre_tablero="<?php echo $reg->nombre_tablero; ?>" onclick="editarImagen(this)">
                                        <span class="material-icons">add_photo_alternate</span>
                                    </button>
                                    <button type="button" class="btn btn-sm text-dark p-0" data-id_tablero="<?php echo $reg->id_tablero; ?>" onclick="editarTablero(this)">
                                        <span class="material-icons">create</span>
                                    </button>
                                </td>
                            </tr>
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
</div>

<!-- Modales -->
<div class="modal fade" id="modalEditarImagen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Imagen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="form_nombre" value="">
                <p><strong>Tamaño:</strong> 200 x 60</p>
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="form_imagen" aria-describedby="Imagen del grupo">
                        <label class="custom-file-label" for="form_imagen">Elegir Archivo</label>
                    </div>
                </div>
                <button id="btn-guardar-edicion" type="button" class="btn btn-primary float-right">Guardar</button>
                <div id="spinner" class="float-right" style="display: none; margin: 8px 10px 8px 0px;">
                    <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarTablero" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tablero</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_editar_tablero">
                    <input type="hidden" id="form2_id_tablero" value="">
                    <p id="form2_nombre_tablero"><strong>Tablero: </strong></p>
                    <div class="form-group">
                        <label for="form2_fecha_publicacion">Fecha de Publicación</label>
                        <input class="form-control form-control-sm" type="date" id="form2_fecha_publicacion" required>
                    </div>
                    <div class="form-group">
                        <label for="form2_desc_tablero">Descripción</label>
                        <textarea class="form-control form-control-sm" id="form2_desc_tablero" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="form2_comercial_responsable">Comercial</label>
                        <input type="text" class="form-control form-control-sm" id="form2_comercial_responsable" required>
                    </div>
                    <div class="form-group">
                        <label for="form2_lider_responsable">Líder</label>
                        <input type="text" class="form-control form-control-sm" id="form2_lider_responsable" required>
                    </div>
                    <div class="form-group">
                        <label for="form2_nombre_pila_cliente">Nombre del Cliente</label>
                        <input type="text" class="form-control form-control-sm" id="form2_nombre_pila_cliente" required>
                        <small>Nombre de la persona a quien se le hizo entrega del tablero por parte del cliente</small>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="form2_actualizacion_automatica">
                        <label class="custom-control-label" for="form2_actualizacion_automatica">¿Actualización automatica?</label>
                    </div>
                    <button id="btn-guardar-edicion-tablero" type="submit" class="btn btn-primary float-right">Guardar</button>
                    <div id="spinnerEditar" class="float-right" style="display: none; margin: 8px 10px 8px 0px;">
                        <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/DataTables/dataTables.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {

        $('#registros').DataTable({
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

        $('#btn-guardar-edicion').click(function() {

            var valor = $("#form_imagen").val();
            if (valor == "") {
                alert("Por favor seleccione una imagen");
                return false;
            }

            //Validamos que solo se suban imagenes
            var allowedExtensions = /(.png)$/i;
            if (!allowedExtensions.exec(valor)) {
                alert('Por favor suba una foto con extensión png');
                $("#form_imagen").val("");
                return false;
            }

            //Validamos el tamaño de la imagen
            var uploadFile = $('#form_imagen')[0].files[0];
            var img = new Image();
            img.src = URL.createObjectURL(uploadFile);
            img.onload = function() {
                if (this.width.toFixed(0) != 200 && this.height.toFixed(0) != 60) {
                    alert('Las medidas deben ser: 200 * 600');
                } else {
                    enviarImagen();
                }
            };

        });

        $('#form_editar_tablero').submit(function(e) {
            guardarEdicion();
            e.preventDefault();
        });

    });

    function enviarImagen() {
        var myformData = new FormData();
        var nombre_tablero = $('#form_nombre').val();
        var imagen_tablero = $('#form_imagen')[0].files[0];

        myformData.append('nombre_tablero', nombre_tablero);
        myformData.append('imagen_tablero', imagen_tablero);

        /*for (var value of myformData.values()) {
            console.log(value);
        }*/

        $.ajax({
            beforeSend: function() {
                $('#spinner').css("display", "inline-block");
            },
            type: "POST",
            url: "index.php?controller=usuario&action=actualizarImagenTablero",
            data: myformData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                //console.log(response);
                var response = JSON.parse(response);
                if (response.estado = "exito") {
                    $('#modalEditarImagen').modal('hide');
                    $('#spinner').css("display", "none");
                    alert("Imagen actualizada correctamente");
                    location.reload();
                } else if (response.estado == "error_subir") {
                    alert("Error: No se ha podido actualizar la imagen");
                } else {
                    alert("Error: No se ha podido actualizar la imagen");
                }
            }
        });
    }

    function editarImagen(elem) {
        var nombre_tablero = $(elem).data('nombre_tablero');
        $('#form_nombre').val(nombre_tablero);
        $('#modalEditarImagen').modal('show');
    }

    function editarTablero(elem) {
        var id_tablero = $(elem).data('id_tablero');
        $('#form2_id_tablero').val(id_tablero);

        $.ajax({
            url: 'index.php?controller=usuario&action=obtenerDatosTablero',
            type: "post",
            data: {
                id_tablero: id_tablero
            },
            success: function(resp) {
                var response = JSON.parse(resp);
                console.log(response);

                $('#form2_nombre_tablero').html("<strong>Tablero: </strong>" + response[0]['nombre_tablero']);
                $('#form2_fecha_publicacion').val(response[0]['fecha_publicación']);
                $('#form2_desc_tablero').val(response[0]['desc_tablero']);
                $('#form2_comercial_responsable').val(response[0]['comercial_responsable']);
                $('#form2_lider_responsable').val(response[0]['lider_responsable']);
                $('#form2_nombre_pila_cliente').val(response[0]['nombre_pila_cliente']);
                if (response[0]['actualizacion_automatica'] == 1) {
                    $('#form2_actualizacion_automatica').attr('checked', true);
                } else {
                    $('#form2_actualizacion_automatica').attr('checked', false);
                }

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log('AJAX Error - ' + errorMessage);
            }
        });

        $('#modalEditarTablero').modal('show');
    }

    function guardarEdicion() {

        var myformData = new FormData();
        var form2_id_tablero = $('#form2_id_tablero').val();
        var form2_fecha_publicacion = $('#form2_fecha_publicacion').val();
        var form2_desc_tablero = $('#form2_desc_tablero').val();
        var form2_comercial_responsable = $('#form2_comercial_responsable').val();
        var form2_lider_responsable = $('#form2_lider_responsable').val();
        var form2_nombre_pila_cliente = $('#form2_nombre_pila_cliente').val();
        if ($('#form2_actualizacion_automatica').is(':checked')) {
            var form2_actualizacion_automatica = 1;
        } else {
            var form2_actualizacion_automatica = 0;
        }

        myformData.append('form2_id_tablero', form2_id_tablero);
        myformData.append('form2_fecha_publicacion', form2_fecha_publicacion);
        myformData.append('form2_desc_tablero', form2_desc_tablero);
        myformData.append('form2_comercial_responsable', form2_comercial_responsable);
        myformData.append('form2_lider_responsable', form2_lider_responsable);
        myformData.append('form2_nombre_pila_cliente', form2_nombre_pila_cliente);
        myformData.append('form2_actualizacion_automatica', form2_actualizacion_automatica);

        /*for (var value of myformData.values()) {
            console.log(value);
        }*/

        $.ajax({
            beforeSend: function() {
                $('#spinnerEditar').css("display", "inline-block");
            },
            type: "POST",
            url: "index.php?controller=usuario&action=actualizarTablero",
            data: myformData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                //console.log(response);
                var response = JSON.parse(response);
                if (response.estado = "exito") {
                    $('#modalEditarTablero').modal('hide');
                    $('#spinnerEditar').css("display", "none");
                    alert("Tablero actualizado correctamente");
                    location.reload();
                } else {
                    alert("Error: No se ha podido actualizar el tablero");
                }
            }
        });
    }
</script>

</body>

</html>