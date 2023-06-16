<?php
$menu = new menu();
$menu->menuGeneral();

?>

<hr />
<div class='row'>
    <div class='col-s12 ml-3 mt-1'>
        <h4 style="color: #162441;">Áreas de Trabajo</h4>
    </div>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="registros" class="table table-sm table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($allGruposTableros) {
                        $btn = '<button type="button" class="btn btn-link">Link</button>';
                        foreach ($allGruposTableros as $reg) {
                            $estado = ($reg->estado_grupo == 1) ? "Activo" : "Inactivo";
                            $imagen = ($reg->imagen_grupo != "No") ? '<a href="assets/img/' . $reg->imagen_grupo . '.png" target="_blank">Ver</a>' : "No se encuentra";
                    ?>
                            <tr>
                                <td><?php echo $reg->nombre_grupo; ?></td>
                                <td><?php echo $imagen; ?></td>
                                <td><?php echo $estado; ?></td>
                                <td class="p-0"><button type="button" class="btn btn-sm text-dark p-0" data-nombre_grupo="<?php echo $reg->nombre_grupo; ?>" onclick="editarImagen(this)"><span class="material-icons">create</span></button></td>
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

    });

    function enviarImagen() {
        var myformData = new FormData();
        var nombre_grupo = $('#form_nombre').val();
        var imagen_grupo = $('#form_imagen')[0].files[0];

        myformData.append('nombre_grupo', nombre_grupo);
        myformData.append('imagen_grupo', imagen_grupo);

        /*for (var value of myformData.values()) {
            console.log(value);
        }*/

        $.ajax({
            beforeSend: function() {
                $('#spinner').css("display", "inline-block");
            },
            type: "POST",
            url: "index.php?controller=usuario&action=actualizarImagenGrupo",
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
                    alert("Error: No se ha podido subir la foto del formato");
                } else {
                    alert("Error: No se ha podido subir la foto del formato");
                }
            }
        });
    }


    function editarImagen(elem) {
        var nombre_grupo = $(elem).data('nombre_grupo');
        $('#form_nombre').val(nombre_grupo);
        $('#modalEditarImagen').modal('show');
    }
</script>

</body>

</html>