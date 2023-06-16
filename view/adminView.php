<?php
$menu = new menu();
$menu->menuGeneral();
?>

<hr/>
<div class='row justify-content-between'>
    <div class='col-s8 ml-3 mt-1'>
        <h4 style="color: #162441;">Accesos a Tableros</h4>
    </div>
    
</div>

<div class="row mt-4" style="justify-content:center">
    <div class="form-group">
        <label for="grupo_tableros">Grupo de tableros</label>
        <select class="form-control" id="grupo_tableros" name="grupo_tableros" onchange="ShowSelected();">
            <option value="" selected="true" disabled="disabled">Elige el grupo de tableros</option>
        <?php
            foreach ($grupos as $grupo) {
            echo "<option value=" . $grupo->id_gt . ">" . $grupo->nombre_grupo . "</option>";
            }
        ?>
        </select>

        </div>
        <div class="form-group">
            <label for="tableros">Tableros</label>
            <select class="form-control" id="tableros" name="tableros" onchange="ShowSelected2();">
            <option value="" selected="true" disabled="disabled">Elige el tablero</option>
        ?>
        </select>
        </div>

        
</div>





<div class="form-group" style="margin: 4% 0 0 33%;">
    <label for="personas" >Personas</label>
    <input type="email" class="form-control" style="width: 230px" id="formPersons" name="form_personas" placeholder="Correo" required>
    <button type="button" class="btn btn-sm text-success btn-add-quit" onclick="agregarPersonsFirst()"><span class="material-icons">add_box</span></button></div>


<div class="row mt-4" style="justify-content:center">

    <div class="form-group">    
       
        <label for="grupo_tableros">Usuarios con Acceso</label>
        <select class="form-control" id="user_access" name="user_access" multiple>
        </select>
        </div>

        <button type="button" class="btn btn-sm text-danger btn-add-quit" onclick="eliminarItemsPersonsAccess()"><span class="material-icons">remove_circle_outline</span></button>

        <button type="button" class="btn btn-sm text-success btn-add-quit" style="justify-content:center" onclick="agregarItemsPersonsAccess()"><span class="material-icons">add_box</span></button>
        
       
        <div class="form-group">

            <label for="tableros">Usuarios sin Acceso</label>
            <select class="form-control" id="user_non_access" name="user_non_access" multiple>
        </select>
        </div>
        
        <div  style="margin: -9% 0 0 80%;">
            
        <a class="buttonNext buttonNextShadow" href="index.php?controller=usuario&action=accessVista" style="color: white;">Ver Grupos Activos</a>
        <a class="buttonNext buttonNextShadow" href="index.php?controller=usuario&action=vermasAccessos" style="color: white;">Ver Tableros Activos</a>
        <button type="button" class="buttonNext buttonNextShadow" data-toggle="modal" data-target="#modalAgregarAccesos">Eliminado General</button>
    <!--<button type="button" class="buttonNext buttonNextShadow">Ver Más</button>--></div>
</div>



<!-- Modales -->
<div class="modal fade" id="modalAgregarAccesos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Accesos General</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_accesos">
                    <div id="lista_personas">
                        <div class="form-group">
                            <label for="grupo_tableros">Grupo de tableros</label>
                            <select class="form-control" id="grupo_tablerosGen" name="grupo_tablerosGen" required multiple data-selected-text-format="count" data-actions-box="true">
                                <?php
                                foreach ($grupos as $grupo) {
                                    echo "<option value=" . $grupo->id_gt . ">" . $grupo->nombre_grupo . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label>Personas</label>
                        <div class="form-group">
                            <input type="email" class="form-control" name="form_personas_del" placeholder="Correo" required>
                            <!--<button type="button" class="btn btn-sm text-success btn-add-quit" onclick="agregarItem()"><span class="material-icons">add_box</span></button>
                            <button type="button" class="btn btn-sm text-danger btn-add-quit" onclick="eliminarItem(this)"><span class="material-icons">remove_circle_outline</span></button>-->
                        </div>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Eliminar">
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

        $('#grupo_tablerosGen').selectpicker();
       

        $('#form_accesos').on('submit', function(e) {

            e.preventDefault();
            var action = "index.php?controller=usuario&action=eliminarAccesosGeneral";
            var grupos_tableros = $('#grupo_tablerosGen').val();
            var emails = document.getElementsByName("form_personas_del")[0].value;
            //var emails = $('#form_accesos input[name="form_personas"]').serializeArray();
            //alert(grupos_tableros);
            //alert(emails);
           
            $.ajax({
                beforeSend: function() {
                    $('#spinner').css("display", "inline-block");
                },
                url: action,
                type: "post",
                data: {
                    "id_grupo": grupos_tableros,
                    "email": emails
                },
                success: function(resp) {
                    //console.log(resp);
                    var response = resp;//JSON.parse(resp);
                    //console.log(response);
                    $('#spinner').css("display", "none");
                    /*if (response == 'exito') {
                        alert("Registros guardados correctamente");
                        location.reload();
                    } else {
                        alert("Ha ocurrido un error al guardar");
                        location.reload();
                    }*/
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    console.log('AJAX Error - ' + errorMessage);
                }
            });
        });

    });
    function ShowSelected()
    {
        var cod = document.getElementById("grupo_tableros").value;
        var select = document.querySelector("#tableros");
        var accesSel = document.querySelector("#user_access");
        //alert(cod);

        $.ajax({
            url: 'index.php?controller=usuario&action=pruebaReceive',
            type: "POST",
            data: {
                "jsones": cod
            },
            success: function(resp) {
                var response = JSON.parse(resp);
                var valores = response[0]['nombre_tablero'];
                
            try{
                var options = document.createElement('option');
                options.value = "";
                options.text = "Elige el tablero";
                options.selected = "true";
                select.appendChild(options);

                for(var tt=0; tt<response.length;tt++)
                {   
                    var idTab = response[tt]['id_tablero'];
                    var option = document.createElement('option');
                    if(response[tt]['nombre_tablero'] == undefined ||  typeof(response[tt]['nombre_tablero']) == 'undefined'){

                        option.value = "Ninguno";
                        option.text = "Tableros No Disponibles";
                        select.appendChild(option);

                    }
                    else{

                        
                        option.value = idTab;
                        option.text = response[tt]['nombre_tablero'];
                        select.appendChild(option);
                        
                    }
                    
                }

            }
            catch(e){
                var option = document.createElement('option');
                option.value = "Ninguno";
                option.text = "Tableros No Disponibles";
                select.appendChild(option);
            }
                
               
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log('AJAX Error - ' + errorMessage);
            }
        });
        for (let i = accesSel.options.length; i >= 0; i--) {

                     accesSel.remove(i);
                     //$('#tableros').selectpicker('refresh');
        }
        for (let i = select.options.length; i >= 0; i--) {

                     select.remove(i);
                     //$('#tableros').selectpicker('refresh');
        }
    }

    function ShowSelected2()
    {
        var cod = document.getElementById("tableros").value;
        var select = document.querySelector("#user_access");

        $.ajax({
            url: 'index.php?controller=usuario&action=pruebaReceive2',
            type: "POST",
            data: {
                "idTab": cod
            },
            success: function(resp) {
                var response = JSON.parse(resp);
                console.log(response);
            try{
                
                for(var tt=0; tt<response.length;tt++)
                {
                    var option = document.createElement('option');
                    
                    if(response[tt]['email_usuario'] == undefined ||  typeof(response[tt]['email_usuario']) == 'undefined'){

                        option.value = "Ninguno";
                        option.text = "Tableros No Disponibles";
                        select.appendChild(option);

                    }
                    else{

                        //console.log(response[tt]['email_usuario']);

                        option.value = response[tt]['email_usuario'];
                        option.text = response[tt]['email_usuario'];
                        select.appendChild(option);
                        //$('#tableros').selectpicker();
                    }
                    
                }
            }
            catch(e){
                var option = document.createElement('option');
                option.value = "Ninguno";
                option.text = "Tableros No Disponibles";
                select.appendChild(option);
            }
                
               
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log('AJAX Error - ' + errorMessage);
            }
        });
        for (let i = select.options.length; i >= 0; i--) {

                     select.remove(i);
                     //$('#tableros').selectpicker('refresh');
        }
    }
    

    function agregarItem() {
        html = "<div class='form-group'><input type='email' class='form-control' name='form_personas' placeholder='Correo' required><button type='button' class='btn btn-sm text-success btn-add-quit' onclick='agregarItem()'><span class='material-icons'>add_box</span></button><button type='button' class='btn btn-sm text-danger btn-add-quit' onclick='eliminarItem(this)'><span class='material-icons'>remove_circle_outline</span></button></div>";
        $('#lista_personas').append(html);
    }


    function agregarPersonsFirst(){
        
        var text = document.getElementsByName("form_personas")[0].value;
        var valor = $("#user_non_access option").val();
        //alert(valor);
        if (valor == text){
            document.getElementById('formPersons').value = "";
            alert("Email ya ingresado");
        }
        else if (valor != text || valor == undefined){
            document.getElementById('formPersons').value = "";
            var select = document.querySelector("#user_non_access");
            
            var option = document.createElement('option');
            option.value = text;
            option.text = text;
            select.appendChild(option);
        }
    
        
    }

    function eliminarItemsPersonsAccess(){

        var resp = confirm("¿Está seguro de eliminar este registro?");
        if (!resp) {
            return false;
        }

        var cod = $('#user_access').val();
        var cod2 = document.getElementById("tableros").value;
        var cod3 = document.getElementById("grupo_tableros").value;
        var select = document.querySelector("#user_non_access");

        //alert(listagen);
        for(var i=0; i<=cod.length;i++){

            if(cod[i] != undefined){
                
                var option = document.createElement('option');
                option.value = cod[i];
                option.text = cod[i];
                select.appendChild(option);

                 $.ajax({
                    url: 'index.php?controller=usuario&action=eliminarTabAccess',
                    type: "POST",
                    data: {
                        "idGroup": cod3,
                        "idTab": cod2,
                        "email": cod[i]
                        },
                    success: function(resp) {
                        var response = resp;//JSON.parse(resp);
                        console.log(response);
                    
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText
                        console.log('AJAX Error - ' + errorMessage);
                    }
                    });
                

	    location.reload();
            $('#user_access option').filter('[value="'+cod[i]+'"]').remove();
                
            }
        }
       
    }

    function agregarItemsPersonsAccess(){

        var arry = [];
        var cod = $('#user_non_access').val();
        var cod2 = document.getElementById("tableros").value;
        var cod3 = document.getElementById("grupo_tableros").value;
        var select = document.querySelector("#user_access");
        //var valor = $("#user_access option").val();
        var values = $("#user_access>option").map(function() { return $(this).val(); });
        
        for (var i = values.length - 1; i >= 0; i--) {
               arry.push(values[i]);
        }
        
        for(var i=0; i<=cod.length;i++){

            if(cod[i] != undefined){
                
                if(arry.includes(cod[i])){
                    alert("Usuario ya registrado");
                }
                else{
                    var option = document.createElement('option');
                    option.value = cod[i];
                    option.text = cod[i];
                    select.appendChild(option);

                     $.ajax({
                        url: 'index.php?controller=usuario&action=agregarTabAccess',
                        type: "POST",
                        data: {
                            "idGroup": cod3,
                            "idTab": cod2,
                            "email": cod[i]
                            },
                        success: function(resp) {
                            var response = resp;//JSON.parse(resp);
                            if(response=="true"){
                                console.log(response);
                            }
                            else{
                                console.log(response);
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText
                            console.log('AJAX Error - ' + errorMessage);
                        }
                        });
                    }
                }
               

            location.reload();
            $('#user_non_access option').filter('[value="'+cod[i]+'"]').remove();
                
            
        }
       
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

        $.ajax({
            url: action,
            type: "POST",
            data: {
                id_usu: id_usu,
                id_grupo: id_grupo
            },
            success: function(resp) {
                console.log(resp);
                var response = JSON.parse(resp);
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

    function eliminarRegistroGeneral(elem) {

        var resp = confirm("¿Está seguro de eliminar este registro?");
        if (!resp) {
            return false;
        }

        var action = "index.php?controller=usuario&action=eliminarAccesosGeneral";
        var id_usu = $(elem).data('id_usu');
        var id_grupo = $(elem).data('id_grupo');

        $.ajax({
            url: action,
            type: "POST",
            data: {
                id_usu: id_usu,
                id_grupo: id_grupo
            },
            success: function(resp) {
                console.log(resp);
                var response = JSON.parse(resp);
                console.log(response);
                /*if (response == 'exito') {
                    alert("Registro eliminado correctamente");
                    location.reload();
                } else {
                    alert("Ha ocurrido un error al eliminar");
                    location.reload();
                }*/
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log('AJAX Error - ' + errorMessage);
            }
        });
    }
</script>

