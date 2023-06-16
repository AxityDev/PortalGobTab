<div class="container">
   <div class="table-wrapper">
      <div class="col-sm-12 p-0">
         <div class="row">
            <div class="col-sm-6">
               <h2 class="mt-3 titulo-pagina">Tableros Power Bi</h2>
            </div>
            <div class="col-sm-6">
               <img class="float-right" src="assets/img/Axity1.png" alt="Axity" style="height:70px;">
            </div>
         </div>
      </div>

      <hr />
      <div id="resultados" style="display:block;"></div><!-- Carga de datos ajax aqui -->

      <div class="card mb-3 card-login">
         <div class="row no-gutters">
            <div class="col-md-8">
               <img src="assets/img/imagen_index.png" class="card-img" alt="..." style="max-width: auto;">
            </div>
            <div class="col-md-4" style="display: flex; align-items: center;;">
               <div class="mx-auto">
                  <br>
                  <div style="display: block; text-align: center; margin-bottom: 20px;">
                     <i class="fa fa-windows" aria-hidden="true" style="font-size: 90px; color: #350c46;"></i>
                  </div>
                  <button id="login" class="btn btn-degrade btn-block" onclick="signIn()">Iniciar con Office 365</button>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>

<!-- Scripts login Microsoft -->
<script type="text/javascript" src="assets/js/authConfig.js"></script>
<script type="text/javascript" src="assets/js/authPopup.js"></script>

</body>

</html>