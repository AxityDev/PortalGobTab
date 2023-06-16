<?php
$menu = new menu();
$menu->menuGeneral();

?>

<hr />
<div class='row justify-content-between'>
    <div class='col-sm-4 mr-1 mt-1'>
        <h4 style="color: #162441;">Áreas de Trabajo</h4>
    </div>
    <div class='col-s-6-m-3 mr-3'>
        <div class='form-group has-search'>
            <span class='fa fa-search form-control-feedback'></span>
            <input id='buscador' type='text' class='form-control' placeholder='Buscar'>
        </div>
    </div>
</div>

<div class='row mt-2' id='resultados'>
    <?php
    if ($allGruposTableros) {
        foreach ($allGruposTableros as $reg) {
    ?>
            <div class='col-sm-3 mb-4'>
                <div class='card text-center card-results'>
                    <div class='card-body'>
                        <img class="mw-100" src='assets/img/<?php echo $reg["nombre_grupo"] . ".png"; ?>'>
                        <h6 class='card-title mt-4 mb-4'><?php echo $reg["nombre_grupo"]; ?></h6>
                        <a href='<?php echo "index.php?controller=usuario&action=tablerosVista&group=" . $reg["grupos_tableros_id_gt"] . "&name=" . $reg["nombre_grupo"]; ?>' class='btn btn-degrade w-100'>CONSULTAR</a>
                    </div>
                </div>
            </div>
    <?php }
    } else {
        echo "<p class='ml-3'>No se encontraron áreas asignadas a tu perfil</p>";
    } ?>
</div>
<div class='clearfix'></div>
</div>
</div>
<script src="assets/js/popper.min.js"></script>
<script>
   
    $(document).ready(function() {
        $("#buscador").keyup(function() {
            texto = $(this).val().toUpperCase();
            if (texto != "") {
                $("#resultados .col-sm-3").each(function() {
                    $(this).hide();
                });
                buscar(texto);
            } else {
                $("#resultados .col-sm-3").each(function() {
                    $(this).show();
                });
            }
        });
    });

    function buscar(texto) {
        $("#resultados :contains('" + texto + "')").parents("div .col-sm-3").show();
    }
</script>

</body>

</html>