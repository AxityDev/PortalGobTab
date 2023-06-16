<?php
class menu
{

    public function menuGeneral()
    {
        $usuario = new Usuario();
        $rol = $usuario->verificarRol();

        echo '<div class="container">
                    <div class="table-wrapper">
                        <div class="col-sm-12 p-0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <!--<h3 class="mt-3" style="color: #783CBD 078dfc;"><b>Tableros Power Bi</b></h3>-->
                                    <h2 class="mt-3 titulo-pagina">Tableros Power Bi</h2>
                                </div>
                                <div class="col-sm-6">
                                    <img class="float-right" src="assets/img/Axity1.png" alt="Axity" style="height:70px;">
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <nav class="navbar navbar-expand-lg navbar-light bg-light">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarColor03">
                                <ul class="navbar-nav mr-auto">
                                <li class="nav-item active">
                                    <a class="nav-link" href="index.php?controller=usuario&action=principalVista">Página Principal <span class="sr-only">(current)</span></a>
                                </li>';
                                if ($rol == "Admin") {
                                    echo '
                                    <li class="nav-item">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item dropdown">

                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div id="spinner_BD" class="spinner-border spinner-border-sm" role="status" style="display: none;">
                                            <span class="sr-only">Loading...</span>
                                            </div>
                                            Administrador
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <a class="dropdown-item" href="index.php?controller=usuario&action=adminVista">
                                            Accesos
                                            </a>
                                            <a class="dropdown-item" href="index.php?controller=usuario&action=rolesVista">
                                            Roles
                                            </a>
                                            <a class="dropdown-item" href="index.php?controller=usuario&action=gruposTablerosVista">
                                            Áreas de Trabajo
                                            </a>
                                            <a class="dropdown-item" href="index.php?controller=usuario&action=adminTablerosVista">
                                            Tableros
                                            </a>
                                            <a id="actualizar_bd" class="dropdown-item" href="#">
                                            Actualizar BD
                                            </a>
                                        </div>
                                        </li>
                                    </ul>
                                    </li>';
                                }
                                
                                echo '</ul>

                                <span class="navbar-text">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-user-circle" aria-hidden="true"></i> '.$_SESSION["nombres"].'
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" href="#" onclick="signOut()"><i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión</a>
                                    </div>
                                    </li>
                                </ul>
                                </span>
                            </div>
                            </nav>';
            echo '<script>
            $(document).ready(function() {
            
              $("#actualizar_bd").click(function() {
          
                var action = "index.php?controller=usuario&action=actualizarBD";
          
                $.ajax({
                  beforeSend: function() {
                    $("#spinner_BD").css("display", "inline-block");
                  },
                  url: action,
                  type: "post",
                  data: {
                    actualizar: "Si"
                  },
                  success: function(resp) {
                    var response = JSON.parse(resp);
                    console.log(response);
                    $("#spinner_BD").css("display", "none");
                    if (response == "exito") {
                      alert("Base de datos actualizada correctamente");
                      location.reload();
                    } else {
                      alert("Ha ocurrido un error");
                      location.reload();
                    }
                  }
                });
              });
          
            });
          </script>';
                            
    }
}

