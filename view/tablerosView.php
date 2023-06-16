<?php
$menu = new menu();
$menu->menuGeneral();

?>

<hr />
<div class="col-sm-12">
    <h4 style="color: #162441;"><?php echo $nombreGrupo; ?></h4>
</div>

<div class="col-sm-12">
    <div class="row mt-4" style="display: flex; flex-wrap: wrap;">
        <?php
        if ($allTableros) {
            foreach ($allTableros as $tablero) {
                $imagen = $tablero->imagen_tablero != "No" ? $tablero->imagen_tablero : $nombreGrupo;
        ?>
                <div class='col-sm-3 mb-4' style="display: flex;">
                    <div class="card text-center card-results">
                        <div class="card-body">
                            <img class="mw-100" src="assets/img/<?php echo $imagen . ".png"; ?>">
                            <div class="bandera bandera-<?php echo strtolower($tablero->pais_tablero); ?>"></div>
                            <span class="mt-4" style="position: relative; top: -5px;"><?php echo $tablero->pais_tablero; ?></span><br>
                            <span class="mt-2"><?php echo $tablero->linea_tablero; ?></span><br>
                            <span class="mt-2 mb-3"><b class="text-secondary"><?php echo $tablero->titulo_tablero; ?></b></span><br>
                            <a href="index.php?controller=usuario&action=reporteVista&id=<?php echo $tablero->id_tablero . "&group=" . $grupo; ?>" class="btn btn-degrade w-100" target="_blank">CONSULTAR</a>
                        </div>
                        <div class="card-footer text-muted">
                            <span data-id_tablero=<?php echo $tablero->id_tablero; ?> onclick="verInformacion(this)">Más Información</span>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='ml-3'>No se encontraron tableros</p>";
        }
        ?>
    </div>
</div>
</div>
</div>

<script src="assets/js/popper.min.js"></script>

<script>
    function verInformacion(elem) {

        id_tablero = $(elem).data('id_tablero');

        $.ajax({
            url: 'index.php?controller=usuario&action=obtenerDatosTablero',
            type: "post",
            data: {
                id_tablero: id_tablero
            },
            success: function(resp) {
                var response = JSON.parse(resp);
                //console.log(response);

                if (response[0]['actualizacion_automatica'] == 1) {
                    actualizacion = "Si";
                } else {
                    actualizacion = "No";
                }

                contenido = "<strong> Fecha de publicación: </strong>" + response[0]['fecha_publicación'] + "<br>";
                contenido += "<strong> Descripción: </strong>" + response[0]['desc_tablero'] + "<br>";
                contenido += "<strong> Comercial: </strong>" + response[0]['comercial_responsable'] + "<br>";
                contenido += "<strong> Líder: </strong>" + response[0]['lider_responsable'] + "<br>";
                contenido += "<strong> Cliente: </strong>" + response[0]['nombre_pila_cliente'] + "<br>";
                contenido += "<strong> ¿Actualización automática?: </strong>" + actualizacion + "<br>";

                $(elem).popover({
                    title: "Más Información",
                    html: true,
                    content: contenido
                });

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