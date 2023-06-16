// Create the main myMSALObj instance
// configuration parameters are located at authConfig.js
var myMSALObj = new Msal.UserAgentApplication(msalConfig);

function signIn() {
  myMSALObj
    .loginPopup(loginRequest)
    .then((loginResponse) => {
      var action = "index.php?controller=usuario&action=login";
      var nombreCompleto = loginResponse["idToken"].name;
      var correo = loginResponse["idToken"].preferredName.toLowerCase();
      
      //Debido a que se ingresa desde diferentes dominios, el nombre viene diferente
      //por lo que toca validar si se separa nombres y apellidos por coma o van todos de una vez
      var nombreSeparado = nombreCompleto.split(",");
      if (nombreSeparado.length == 2) {
        nombreCompleto =
          nombreSeparado[1].trim() + " " + nombreSeparado[0].trim();
        var nombre = nombreSeparado[1].trim();
      } else {
        var nombre = nombreCompleto.split(" ")[0].trim();
      }

      /*console.log("NC: " + nombreCompleto);
      console.log("N: " + nombre);
      console.log("C: " + correo);*/

      $.ajax({
        url: action,
        type: "post",
        data: {
          nombreCompleto: nombreCompleto,
          nombre: nombre,
          correo: correo,
        },
        success: function (resp) {
          if ((resp = "exito")) {
            var href = window.location.href;
            if (href.includes("index.php")) {
              var url = href.replace(
                "index.php",
                "index.php?controller=usuario&action=principalVista"
              );
            } else {
              var url =
                href + "index.php?controller=usuario&action=principalVista";
            }
            window.location.assign(url);
          } else {
            $("#resultados").html(
              "<div class='alert alert-danger' role='alert'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Error! </strong>No se pudo iniciar sesión, por favor intente nuevamente.</div>"
            );
          }
        },
        error: function (xhr, status, error) {
          var errorMessage = xhr.status + ": " + xhr.statusText;
          console.log("AJAX Error - " + errorMessage);
        },
      });
    })
    .catch((error) => {
      console.log(error);
      $("#resultados").html(
        "<div class='alert alert-danger' role='alert'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Error! </strong>No se pudo iniciar sesión, por favor intente nuevamente.</div>"
      );
    });
}

function signOut() {
  var action = "index.php?controller=usuario&action=cerrarSesion";

  $.ajax({
    url: action,
    type: "post",
    data: {
      logout: "Si",
    },
    success: function (resp) {
      resp = resp.trim();
      console.log(resp);
      if (resp == "exito") {
        myMSALObj.logout();
      } else {
        alert("Ha ocurrido un error");
      }
    },
  });
}

function getTokenPopup(request) {
  return myMSALObj.acquireTokenSilent(request).catch((error) => {
    console.log(error);
    console.log("silent token acquisition fails. acquiring token using popup");
    // fallback to interaction when silent call fails
    return myMSALObj
      .acquireTokenPopup(request)
      .then((tokenResponse) => {
        return tokenResponse;
      })
      .catch((error) => {
        console.log(error);
      });
  });
}
