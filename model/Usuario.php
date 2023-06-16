<?php
class Usuario extends EntidadBase
{
  private $id_usuario;
  private $email_usuario;
  private $nombre_usuario;
  private $fk_id_rol;

  public function __construct()
  {
    $table = 'usuarios';
    $id = 'id_usuario';
    parent::__construct($table, $id);
  }

  /*-----------------------------GETTERS & SETTERS-----------------------------*/

  public function __GET($k)
  {
    return $this->$k;
  }

  public function __SET($k, $v)
  {
    $this->$k = $v;
  }

  /*--------------------------------------------------------------*/

  public function getAllJoin()
  {
    try {
      $query = "SELECT t1.*, t2.nombre_rol
                      FROM usuarios t1 
                      LEFT JOIN roles t2
                      ON (t1.fk_id_rol = t2.id_rol)";

      $stmt = $this->db()->prepare($query);
      $stmt->execute();

      $result = [];

      foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
        $result[] = $r;
      }

      return $result;
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  public function registrarUsuario($correo, $nombreCompleto)
  {
    $sql_verifica_registro = "SELECT id_usuario FROM usuarios WHERE email_usuario = ?";

    $sql_insertar = "INSERT INTO usuarios (email_usuario, nombre_usuario, fk_id_rol) VALUES (?, ?, 2)";
    $sql_update = "UPDATE usuarios SET nombre_usuario = ? WHERE email_usuario = ?";
    
    //Verificar si ya existe el usuario en BD
    $stmt = $this->db()->prepare($sql_verifica_registro);
    $stmt->bindParam(1, $correo, PDO::PARAM_STR);
    $stmt->execute();
    $num_filas = count($stmt->fetchAll());

    //Si no hay resultados, se inserta el acceso al grupo
    if ($num_filas == 0) {
      $stmt = $this->db()->prepare($sql_insertar);
      $stmt->bindParam(1, $correo, PDO::PARAM_STR);
      $stmt->bindParam(2, $nombreCompleto, PDO::PARAM_STR);
      $result = $stmt->execute();
    } else {
      $stmt = $this->db()->prepare($sql_update);
      $stmt->bindParam(1, $nombreCompleto, PDO::PARAM_STR);
      $stmt->bindParam(2, $correo, PDO::PARAM_INT);
      $result = $stmt->execute();
    }

    if (!$result) {
      return false;
    } else {
      return true;
    }
  }

  public function verificarSession()
  {
    try {

      session_start();

      if (isset($_SESSION["correo"])) {
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  public function verificarRol()
  {
    try {

      if (isset($_SESSION["correo"])) {

        $correo = $_SESSION['correo'];

        $sql = "SELECT t2.nombre_rol FROM usuarios t1 LEFT JOIN roles t2 ON (t1.fk_id_rol = t2.id_rol) WHERE email_usuario = ?";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(1, $correo, PDO::PARAM_STR);
        $stmt->execute();

        //Obtenemos el token y tipo de usuario
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $rol = $result['nombre_rol'];

        return $rol;
      } else {
        return false;
      }
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  //Obtiene todos los grupos de la API PowerBI
  public function obtenerGruposAPI()
  {
    //Obtenemos el token
    $token = $this->consultaToken();
    $request_url = 'https://api.powerbi.com/v1.0/myorg/groups';
    $grupos = $this->consultar_api_pbi($request_url, $token);

    return $grupos;
  }

  //Obtiene un token de PowerBI y lo inserta en BD
  private function generar_token()
  {

    $curlPostToken = curl_init();

    curl_setopt_array($curlPostToken, array(
      CURLOPT_URL => "https://login.windows.net/common/oauth2/token",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => array(
        'grant_type' => 'password',
        'scope' => 'openid',
        'resource' => 'https://analysis.windows.net/powerbi/api',
        'client_id' => '0e6d6fdc-a986-484c-9ba0-760dac670678', // Registered App ApplicationID
        'client_secret' => 'W1bXH0.Gw4c2w.._Ot-uaf4~r5ID72WtX.',
        'username' => 'bi.colombia@axity.tech', // for example john.doe@yourdomain.com
        'password' => 'C0l0mb148i.2O19' // Azure password for above user
      )
    ));

    $tokenResponse = curl_exec($curlPostToken);
    $tokenError = curl_error($curlPostToken);
    curl_close($curlPostToken);

    // decode result, and store the access_token in $embeddedToken variable:
    $tokenResult = json_decode($tokenResponse, true);
    $tokenError = json_decode($tokenError, true);

    $token_type = $tokenResult["token_type"];
    $expires_in = $tokenResult["expires_in"];
    $ext_expires_in = $tokenResult["ext_expires_in"];
    $expires_on = $tokenResult["expires_on"];
    $not_before = $tokenResult["not_before"];
    $resource = $tokenResult["resource"];
    $access_token = $tokenResult["access_token"];
    $refresh_token = $tokenResult["refresh_token"];
    $id_token = $tokenResult["id_token"];

    /*echo "token_type: " . strlen($token_type) . "<br>";
    echo "expires_in: " . strlen($expires_in) . "<br>";
    echo "ext_expires_in: " . strlen($ext_expires_in) . "<br>";
    echo "expires_on: " . strlen($expires_on) . "<br>";
    echo "not_before: " . strlen($not_before) . "<br>";
    echo "resource: " . strlen($resource) . "<br>";
    echo "access_token: " . strlen($access_token) . "<br>";
    echo "refresh_token: " . strlen($refresh_token) . "<br>";
    echo "id_token: " . strlen($id_token) . "<br>";*/

    $consultaSQL = "UPDATE api_token 
                    SET token_type = ?, 
                        expires_in = ?,
                        ext_expires_in = ?,
                        expires_on = ?,
                        not_before = ?,
                        resource = ?,
                        access_token = ?,
                        refresh_token = ?,
                        id_token = ?
                        WHERE id = 1";

    $stmt = $this->db()->prepare($consultaSQL);
    $stmt->bindParam(1, $token_type, PDO::PARAM_STR);
    $stmt->bindParam(2, $expires_in, PDO::PARAM_STR);
    $stmt->bindParam(3, $ext_expires_in, PDO::PARAM_STR);
    $stmt->bindParam(4, $expires_on, PDO::PARAM_STR);
    $stmt->bindParam(5, $not_before, PDO::PARAM_STR);
    $stmt->bindParam(6, $resource, PDO::PARAM_STR);
    $stmt->bindParam(7, $access_token, PDO::PARAM_STR);
    $stmt->bindParam(8, $refresh_token, PDO::PARAM_STR);
    $stmt->bindParam(9, $id_token, PDO::PARAM_STR);
    $stmt->execute();
  }

  //Valida si el token de PowerBI expiró
  private function validaToken()
  {

    $consultaToken = "SELECT expires_on FROM api_token WHERE id = 1";

    $stmt = $this->db()->prepare($consultaToken);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $expires_on = $result['expires_on'];

    $expires_on = $expires_on - 17995;
    $dt = new DateTime("@$expires_on");
    $dt = $dt->format('Y-m-d H:i:s');
    $hora_actual = date('Y-m-d H:i:s');

    //Si el token expiró, llama a la función generar_token para generar uno nuevo
    if ($dt < $hora_actual) {
      $this->generar_token();
    }
  }

  //Consulta en BD el token de PowerBI
  public function consultaToken()
  {
    //Primero valida si el token de PowerBI expiró
    $this->validaToken();

    //Una vez verificado el token, lo extrae de la BD
    $consultaToken = "SELECT access_token FROM api_token WHERE id = 1";
    $stmt = $this->db()->prepare($consultaToken);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $access_token = $result['access_token'];

    return $access_token;
  }

  public function consultar_api_pbi($request_url, $access_token)
  {

    $curl = curl_init($request_url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      "Authorization : Bearer $access_token",
      "Content-Type: application/json"
    ]);

    $tokenResponse = curl_exec($curl);
    $tokenError = curl_error($curl);
    curl_close($curl);

    // decode result, and store the access_token in $embeddedToken variable:
    $tokenResult = json_decode($tokenResponse, true);
    $tokenError = json_decode($tokenError, true);
    return $tokenResult;
  }

  public function actualizarBD()
  {
    $access_token = $this->consultaToken();

    //Consultamos de la API todos los grupos
    $request_url = 'https://api.powerbi.com/v1.0/myorg/groups';
    $grupos = $this->consultar_api_pbi($request_url, $access_token);
    if (!isset($grupos)) {
      return "error";
    }

    //Consultamos todos los grupos en BD y los almacenamos en un array
    $sql_traer_grupos = "SELECT id_gt FROM grupos_tableros";
    $stmt = $this->db()->prepare($sql_traer_grupos);
    $result = $stmt->execute();
    if (!$result) {
      return "error";
    }

    $gruposBD = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
      $gruposBD[$r['id_gt']] = $r;
    }

    //Consultamos todos los tableros en BD y los almacenamos en un array
    $sql_traer_tableros = "SELECT id_tablero FROM tableros";
    $stmt = $this->db()->prepare($sql_traer_tableros);
    $result = $stmt->execute();
    if (!$result) {
      return "error";
    }

    $tablerosBD = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
      $tablerosBD[$r['id_tablero']] = $r;
    }

    //Actualizamos el estado de todos los grupos a 0 (inactivo) 
    $sql = "UPDATE grupos_tableros SET estado_grupo = 0";
    $stmt = $this->db()->prepare($sql);
    $result = $stmt->execute();
    if (!$result) {
      return "error";
    }

    //Actualizamos todos los tableros 
    $sql = "UPDATE tableros SET estado_tablero = 0";
    $stmt = $this->db()->prepare($sql);
    $result = $stmt->execute();
    if (!$result) {
      return "error";
    }

    //Validamos si ya se encuentra en BD el grupo y los tableros que contiene, si no, lo insertamos
    foreach ($grupos["value"] as $index) {

      //Si no está el grupo en BD, lo insertamos en BD
      if (!isset($gruposBD[$index["id"]])) {
        $sql = "INSERT INTO grupos_tableros VALUES (?,?,1)";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(1, $index["id"], PDO::PARAM_STR);
        $stmt->bindParam(2, $index["name"], PDO::PARAM_STR);
        $result = $stmt->execute();
        if (!$result) {
          break;
          return "error";
        }
      } else {
        //Si está, actualizamos los campos
        $sql_update = "UPDATE grupos_tableros SET nombre_grupo = ?, estado_grupo = 1 WHERE id_gt = ?";
        $stmt = $this->db()->prepare($sql_update);
        $stmt->bindParam(1, $index["name"], PDO::PARAM_STR);
        $stmt->bindParam(2, $index["id"], PDO::PARAM_STR);
        $result = $stmt->execute();
        if (!$result) {
          break;
          return "error";
        }
      }

      //Consultamos todos los tableros del grupo
      $request_url = 'https://api.powerbi.com/v1.0/myorg/groups/' . $index["id"] . '/reports';
      $tablerosGrupo = $this->consultar_api_pbi($request_url, $access_token);

      //Si el grupo tiene tableros, los recorremos e insertamos en BD
      if (isset($tablerosGrupo["value"])) {
        foreach ($tablerosGrupo["value"] as $tableroGrupo) {

          $cliente = explode("_", $tableroGrupo['name'])[0];
          $pais = (isset(explode("_", $tableroGrupo['name'])[1])) ? explode("_", $tableroGrupo['name'])[1] : "";
          $linea = (isset(explode("_", $tableroGrupo['name'])[2])) ? explode("_", $tableroGrupo['name'])[2] : "";
          $titulo = (isset(explode("_", $tableroGrupo['name'])[3])) ? explode("_", $tableroGrupo['name'])[3] : "";

          //Si no está el tablero en BD, lo insertamos en BD
          if (!isset($tablerosBD[$tableroGrupo["id"]])) {
            $sql = "INSERT INTO tableros VALUES (?,?,?,?,?,?,?,1,?, NULL, NULL, NULL, NULL, NULL, NULL)";
            $stmt = $this->db()->prepare($sql);
            $stmt->bindParam(1, $tableroGrupo["id"], PDO::PARAM_STR);
            $stmt->bindParam(2, $tableroGrupo['name'], PDO::PARAM_STR);
            $stmt->bindParam(3, $cliente, PDO::PARAM_STR);
            $stmt->bindParam(4, $pais, PDO::PARAM_STR);
            $stmt->bindParam(5, $linea, PDO::PARAM_STR);
            $stmt->bindParam(6, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(7, $tableroGrupo['webUrl'], PDO::PARAM_STR);
            $stmt->bindParam(8, $index["id"], PDO::PARAM_STR);
            $result = $stmt->execute();
            if (!$result) {
              break;
              return "error";
            }
          } else {
            //Si ya está en BD, actualizamos los campos
            $sql = "UPDATE tableros SET nombre_tablero=?,cliente_tablero=?,pais_tablero=?,linea_tablero=?,titulo_tablero=?,url_tablero=?,estado_tablero=1,fk_id_gt=? WHERE id_tablero = ?";
            $stmt = $this->db()->prepare($sql);
            $stmt->bindParam(1, $tableroGrupo["name"], PDO::PARAM_STR);
            $stmt->bindParam(2, $cliente, PDO::PARAM_STR);
            $stmt->bindParam(3, $pais, PDO::PARAM_STR);
            $stmt->bindParam(4, $linea, PDO::PARAM_STR);
            $stmt->bindParam(5, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(6, $tableroGrupo['webUrl'], PDO::PARAM_STR);
            $stmt->bindParam(7, $index["id"], PDO::PARAM_STR);
            $stmt->bindParam(8, $tableroGrupo["id"], PDO::PARAM_STR);
            $result = $stmt->execute();
            if (!$result) {
              break;
              return "error";
            }
          }
        }
      }
    }

    return "exito";
  }

  public function eliminarAccesos($id_usu, $id_grupo, $email)
  {
    $resp = "exito";

    $query = "SELECT t1.id_tablero FROM tableros AS t1 LEFT JOIN accesos_tableros AS t2 ON (t1.id_tablero = t2.fk_id_tablero) WHERE fk_id_gt = ? AND email_usuario = ? ORDER BY nombre_tablero";

    $stmt = $this->db()->prepare($query);
    $stmt->bindParam(1, $id_grupo, PDO::PARAM_STR);
    $stmt->bindParam(2, $email, PDO::PARAM_STR);
    $stmt->execute();
    $resultE = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $sqlER = "DELETE FROM accesos_tableros WHERE email_usuario = ? AND fk_id_tablero = ?";
    $stmt = $this->db()->prepare($sqlER);
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->bindParam(2, $resultE['id_tablero'], PDO::PARAM_STR);
    $resultER = $stmt->execute();


    $sql = "DELETE FROM usuarios_has_grupos_tableros WHERE usuarios_id_usuario = ? AND grupos_tableros_id_gt = ?";
    $stmt = $this->db()->prepare($sql);
    $stmt->bindParam(1, $id_usu, PDO::PARAM_INT);
    $stmt->bindParam(2, $id_grupo, PDO::PARAM_STR);
    $result = $stmt->execute();

    if (!$result) {
      $resp = "falla";
    }
    return $resp;
  }

  public function eliminarAccesosGenerales($id_grupo, $email)
  {
    $resp = "exito";
    foreach ($id_grupo as $id_grupos) {
      
      $querys = "SELECT t1.id_tablero FROM tableros AS t1 LEFT JOIN accesos_tableros AS t2 ON (t1.id_tablero = t2.fk_id_tablero) WHERE fk_id_gt = ? AND email_usuario = ? ORDER BY nombre_tablero";

      $stmt = $this->db()->prepare($querys);
      $stmt->bindParam(1, $id_grupos, PDO::PARAM_STR);
      $stmt->bindParam(2, $email, PDO::PARAM_STR);
      $stmt->execute();
      $resultE = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $varf = count($resultE);
      
    }
    
    for ($i=0; $i <= $varf; $i++) { 
         $sqlER = "DELETE FROM accesos_tableros WHERE email_usuario = ? AND fk_id_tablero = ?";
          $stmt = $this->db()->prepare($sqlER);
          $stmt->bindParam(1, $email, PDO::PARAM_STR);
          $stmt->bindParam(2, $resultE[$i]['id_tablero'], PDO::PARAM_STR);
          $resultER = $stmt->execute(); 
    }
    
    $sqlConUsu = "SELECT id_usuario FROM usuarios WHERE email_usuario=?";
    $stmt = $this->db()->prepare($sqlConUsu);
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $resp = $stmt->fetch(PDO::FETCH_ASSOC);

    foreach ($id_grupo as $id_grupos) {
      $sql = "DELETE FROM usuarios_has_grupos_tableros WHERE usuarios_id_usuario = ? AND grupos_tableros_id_gt = ?";

      $stmt = $this->db()->prepare($sql);
      $stmt->bindParam(1, $resp['id_usuario'], PDO::PARAM_INT);
      $stmt->bindParam(2, $id_grupos, PDO::PARAM_STR);
      $result = $stmt->execute();
    }
    /*if (!$result) {
      $resp = "falla";
    }*/
    return $result;
  }

  public function eliminarAccesosTab($emails_usuarios,$fk_id_tablero){

    $resp = "exito";

    $sql = "DELETE FROM accesos_tableros WHERE email_usuario = ? AND fk_id_tablero = ?";
    $stmt = $this->db()->prepare($sql);
    $stmt->bindParam(1, $emails_usuarios, PDO::PARAM_INT);
    $stmt->bindParam(2, $fk_id_tablero, PDO::PARAM_STR);
    $result = $stmt->execute();

    if (!$result) {
      $resp = "falla";
    }

    return $result;

  }

  public function agregarAccesosTab($emails_usuarios,$fk_id_tablero){

    $resp = "exito";

    $sql = "INSERT INTO accesos_tableros (fecha_at, email_usuario, fk_id_tablero) VALUES (GETDATE(), ?, ?)";
    $stmt = $this->db()->prepare($sql);
    $stmt->bindParam(1, $emails_usuarios, PDO::PARAM_INT);
    $stmt->bindParam(2, $fk_id_tablero, PDO::PARAM_STR);
    $result = $stmt->execute();

    if (!$result) {
      $resp = "falla";
    }

    return $result;

  }



  public function guardarAccesos($grupos_tableros, $emails)
  {
    //$sql_verifica_usuario = "SELECT COUNT(id_usuario) AS conteo, id_usuario FROM usuarios WHERE email_usuario = ? GROUP BY id_usuario";

    $id_usuario = "SELECT id_usuario FROM usuarios WHERE email_usuario = ?";
    $stmt = $this->db()->prepare($id_usuario);
    $stmt->bindParam(1, $emails, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $idUser = $result['id_usuario'];

    $consultGroupForUser = "SELECT * FROM usuarios_has_grupos_tableros WHERE usuarios_id_usuario=? AND grupos_tableros_id_gt=?";
    $stmt = $this->db()->prepare($consultGroupForUser);
    $stmt->bindParam(1, $idUser, PDO::PARAM_STR);
    $stmt->bindParam(2, $grupos_tableros, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['grupos_tableros_id_gt'] == $grupos_tableros){
      return 'son iguales';
    }
    else{
      $sql = "INSERT INTO usuarios_has_grupos_tableros VALUES (?, ?)";
      $stmt = $this->db()->prepare($sql);
      $stmt->bindParam(1, $idUser, PDO::PARAM_INT);
      $stmt->bindParam(2, $grupos_tableros, PDO::PARAM_STR);
      $stmt->execute();
      return 'guardado';
    }
    //return $idUser;
  }

  public function eliminarGenAccesos($grupos_tableros, $emails){
    
  }

  public function guardarRoles($id_rol, $emails)
  {
    $resp = "exito";
    $sql_verifica_usuario = "SELECT COUNT(id_usuario) AS conteo, id_usuario FROM usuarios WHERE email_usuario = ? GROUP BY id_usuario";

    for ($i = 0; $i < count($emails); $i++) {

      $email = $emails[$i]['value'];

      //Verificar si el email ya se encuentra registrado en la tabla usuarios
      $stmt = $this->db()->prepare($sql_verifica_usuario);
      $stmt->bindParam(1, $email, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $num_filas = $result['conteo'];
      $id_usuario = $result['id_usuario'];

      //Si hay resultados, se asocia el ID del usuario, si no.. se inserta un nuevo usuario
      if ($num_filas == 0) {
        $sql = "INSERT INTO usuarios (email_usuario, fk_id_rol ) VALUES (?,?)";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $id_rol, PDO::PARAM_INT);
        $result = $stmt->execute();
        $id_usuario = $this->db()->lastInsertId();
      } else {
        $sql = "UPDATE usuarios SET fk_id_rol = ? WHERE id_usuario = ?";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(1, $id_rol, PDO::PARAM_INT);
        $stmt->bindParam(2, $id_usuario, PDO::PARAM_STR);
        $result = $stmt->execute();
      }

      if (!$result) {
        $resp = "falla";
        break;
      }
    }

    return $resp;
  }

  public function obtenerAccesos()
  {
    try {
      $query = "SELECT t1.*, t2.nombre_grupo, t3.email_usuario, t2.estado_grupo 
                FROM usuarios_has_grupos_tableros t1 
                LEFT JOIN grupos_tableros t2 
                ON (t1.grupos_tableros_id_gt = t2.id_gt) 
                LEFT JOIN usuarios t3 
                ON (t1.usuarios_id_usuario = t3.id_usuario) 
                ORDER BY t2.nombre_grupo";

      $stmt = $this->db()->prepare($query);
      $stmt->execute();

      $result = [];

      foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $result[] = $r;
      }

      return $result;
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }


  public function obtenerAccesosTab($fk_id_tablero){
    

      $query = "SELECT fk_id_tablero FROM accesos_tableros WHERE fk_id_tablero= ?";
      $stmt = $this->db()->prepare($query);
      $stmt->bindParam(1, $fk_id_tablero, PDO::PARAM_STR);
      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $result;

   
  }


  public function registrarAccesoGrupo($id_gt = NULL, $email = NULL)
  {

    $sql_insertar = "INSERT INTO accesos_grupos (fecha_ag, email_usuario, fk_id_gt) VALUES (GETDATE(), ?, ?)";
    $stmt = $this->db()->prepare($sql_insertar);
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->bindParam(2, $id_gt, PDO::PARAM_STR);
    $result = $stmt->execute();

    if (!$result) {
      return false;
    } else {
      return true;
    }
  }

  public function getAllUsersWithTab($fk_id_tablero){

    $consultaUser = "SELECT email_usuario FROM accesos_tableros where fk_id_tablero=? ORDER BY email_usuario ASC";
    $stmt = $this->db()->prepare($consultaUser);
    $stmt->bindParam(1,$fk_id_tablero, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result;
  }

  public function getAllForTabAcc()
    {
        try {
            $query = "SELECT t1.cliente_tablero,t1.nombre_tablero, t2.email_usuario, t4.nombre_rol FROM tableros AS t1 LEFT JOIN accesos_tableros AS t2 ON (t1.id_tablero = t2.fk_id_tablero) LEFT JOIN usuarios AS t3 ON(t2.email_usuario = t3.email_usuario) LEFT JOIN roles AS t4 ON (t3.fk_id_rol = t4.id_rol) WHERE estado_tablero='1' ORDER BY nombre_tablero";

            $stmt = $this->db()->prepare($query);
            $stmt->execute();
            $results = [];

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $results[] = $r;
            }

            return $results;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

public function getAllForTabValid($fk_id_gt,$email)
    {
        try {
            $query = "SELECT t2.email_usuario FROM tableros AS t1 LEFT JOIN accesos_tableros AS t2 ON (t1.id_tablero = t2.fk_id_tablero) WHERE fk_id_gt= ? and email_usuario= ? and estado_tablero='1' ORDER BY nombre_tablero";

            $stmt = $this->db()->prepare($query);
            $stmt->bindParam(1, $fk_id_gt, PDO::PARAM_STR);
            $stmt->bindParam(2, $email, PDO::PARAM_STR);
            $stmt->execute();
            $resulter = $stmt->fetch(PDO::FETCH_ASSOC);
            //return $result['email_usuario'];
            if($resulter['email_usuario'] == $email){
                    $result = true;
                    return $result;
                }
                else{
                    $result = false;
                    return $result;
                }
 
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }



  public function registrarAccesoTablero($id_tablero = NULL, $email = NULL)
  {
    $sql_insertar = "INSERT INTO tablerosAccesos (fecha_at, email_usuario, fk_id_tablero) VALUES (GETDATE(), ?, ?)";
    /*$sql_insertar = "INSERT INTO accesos_tableros (fecha_at, email_usuario, fk_id_tablero) VALUES (GETDATE(), ?, ?)";*/
    $stmt = $this->db()->prepare($sql_insertar);
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->bindParam(2, $id_tablero, PDO::PARAM_STR);
    $result = $stmt->execute();

    if (!$result) {
      return false;
    } else {
      return true;
    }
  }
}
