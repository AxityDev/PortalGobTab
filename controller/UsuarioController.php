<?php
class UsuarioController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    public function index()
    {
        session_start();
        if (isset($_SESSION['correo'])) {
            header("location:index.php?controller=usuario&action=principalVista");
        } else {
            $this->view("login");
        }
    }

    public function login()
    {
        if (isset($_POST["nombreCompleto"])) {

            session_start();

            $_SESSION['correo'] = $_POST["correo"];
            $_SESSION['nombres'] = $_POST["nombre"];

            //Registramos el usuario en BD por si la persona nunca había ingresado al sistema
            $usuario = new Usuario();
            $res = $usuario->registrarUsuario($_POST['correo'], $_POST['nombreCompleto']);
            
            //Verificar si efectivamente se creó la sesión
            if (isset($_SESSION['correo']) && $_SESSION['correo'] != "" && $res) {
                echo "exito";
            }
        } else {
            $this->redirect();
        }
    }

    public function cerrarSesion()
    {
        try {
            session_start();
            if (!empty($_SESSION["nombres"])) {
                session_destroy();
                session_unset();
                //$this->redirect();
                echo "exito";
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function principalVista()
    {

        //Creamos el objeto usuario
        $usuario = new Usuario();

        if ($usuario->verificarSession()) {

            $gruposTableros = new GrupoTablero();
            $email = $_SESSION['correo'];

            //Conseguimos todos los datos
            $allGruposTableros = $gruposTableros->traerGruposTableros($email);

            //Cargamos la vista y le pasamos valores
            $this->view("principal", array(
                'allGruposTableros' => $allGruposTableros
            ));
        } else {
            $this->view("login");
        }
    }

    public function tablerosVista()
    {

        //Creamos el objeto usuario
        $usuario = new Usuario();

        if ($usuario->verificarSession()) {
            if (isset($_GET["group"])) {

                $grupo = $_GET["group"];
                $nombreGrupo = $_GET["name"];
                $email = $_SESSION['correo'];

                //Registramos el acceso al grupo de tableros
                
                $tableros = new Tablero();

                    //Conseguimos todos los datos
                    $allTableros = $tableros->getAllForTab($grupo,$email);

                    //Cargamos la vista y le pasamos valores
                    $this->view("tableros", array(
                        'allTableros' => $allTableros,
                        'nombreGrupo' => $nombreGrupo,
                        'grupo' => $grupo
                    ));
                

                
            }
        } else {
            $this->view("login");
        }
    }

    public function reporteVista()
    {

        //Creamos el objeto usuario
        $usuario = new Usuario();

        if ($usuario->verificarSession()) {


            $id_reporte = $_GET["id"];
            $group = $_GET["group"];
            $email = $_SESSION['correo'];

            $usuario = new Usuario();

            //Registramos el acceso al tablero

            $usuario->registrarAccesoTablero($id_reporte, $email);

            //Conseguimos el token
            $access_token = $usuario->consultaToken();

            //Cargamos la vista y le pasamos valores
            $this->view("reporte", array(
                'id_reporte' => $id_reporte,
                'group' => $group,
                'access_token' => $access_token
            ), "no_header");
        } else {
            $this->view("login");
        }
    }

    public function adminVista()
    {

        //Creamos el objeto usuario
        $usuario = new Usuario();

        if ($usuario->verificarSession()) {

            $grupoTablero = new GrupoTablero();

            //Conseguimos todos los datos
            $grupos = $grupoTablero->getForCombox();
            //$grupos = $usuario->obtenerGruposAPI();
            $registrosAccesos = $usuario->obtenerAccesos();

            //Cargamos la vista y le pasamos valores
            $this->view("admin", array(
                'grupos' => $grupos,
                'registrosAccesos' => $registrosAccesos
            ));
        } else {
            $this->view("login");
        }
    }


    public function accessVista(){
        $usuario = new Usuario();

        if ($usuario->verificarSession()) {

            $grupoTablero = new GrupoTablero();

            //Conseguimos todos los datos
            $grupos = $grupoTablero->getForCombox();
            //$grupos = $usuario->obtenerGruposAPI();
            $registrosAccesos = $usuario->obtenerAccesos();

            //Cargamos la vista y le pasamos valores
            $this->view("access", array(
                'grupos' => $grupos,
                'registrosAccesos' => $registrosAccesos
            ));
        } else {
            $this->view("login");
        }
    }


    public function rolesVista()
    {

        //Creamos el objeto usuario
        $usuario = new Usuario();

        if ($usuario->verificarSession()) {

            $rol = new Rol();

            //Conseguimos todos los datos
            $allRoles = $rol->getALL();
            $registrosRoles = $rol->getAllJoin();

            //Cargamos la vista y le pasamos valores
            $this->view("roles", array(
                'roles' => $allRoles,
                'registrosRoles' => $registrosRoles
            ));
        } else {
            $this->view("login");
        }
    }

    public function gruposTablerosVista()
    {

        //Creamos el objeto usuario
        $usuario = new Usuario();

        if ($usuario->verificarSession()) {

            $grupoTablero = new GrupoTablero();

            //Conseguimos todos los datos
            $allGruposTableros = $grupoTablero->getALL();

            //Cargamos la vista y le pasamos valores
            $this->view("gruposTableros", array(
                'allGruposTableros' => $allGruposTableros
            ));
        } else {
            $this->view("login");
        }
    }

    public function adminTablerosVista()
    {

        //Creamos el objeto usuario
        $usuario = new Usuario();

        if ($usuario->verificarSession()) {

            $tablero = new Tablero();

            //Conseguimos todos los datos
            $allTableros = $tablero->getALL();

            //Cargamos la vista y le pasamos valores
            $this->view("adminTableros", array(
                'allTableros' => $allTableros
            ));
        } else {
            $this->view("login");
        }
    }

    public function actualizarBD()
    {
        if (isset($_POST['actualizar'])) {
            $usuario = new Usuario();
            $resp = $usuario->actualizarBD();
            echo json_encode($resp);
        }
    }

    public function eliminarAccesos()
    {
        if (isset($_POST['id_usu'])) {
            $id_usu = $_POST['id_usu'];
            $id_grupo = $_POST['id_grupo'];
            $email_usuarios = $_POST['email'];

            $usuario = new Usuario();
            $resp = $usuario->eliminarAccesos($id_usu, $id_grupo, $email_usuarios);
            echo json_encode($resp);
        }
    }

    public function eliminarAccesosGeneral()
    {
        
            $id_grupo = $_POST['id_grupo'];
            $email_usuarios = $_POST['email'];

            $usuario = new Usuario();
            $resp = $usuario->eliminarAccesosGenerales($id_grupo, $email_usuarios);
            echo json_encode($resp);
        
    }

    public function guardarAccesos()
    {
        if (isset($_POST['grupos_tableros'])) {

            $grupos_tableros = $_POST['grupos_tableros'];
            $emails = $_POST['emails'];

            $usuario = new Usuario();
            $resp = $usuario->guardarAccesos($grupos_tableros, $emails);
            echo json_encode($resp);
        }
    }

    public function eliminarGenAccesos()
    {
        if (isset($_POST['grupos_tableros'])) {

            $grupos_tableros = $_POST['grupos_tableros'];
            $emails = $_POST['emails'];

            $usuario = new Usuario();
            $resp = $usuario->guardarAccesos($grupos_tableros, $emails);
            echo json_encode($resp);
        }
    }

    public function guardarRoles()
    {
        if (isset($_POST['id_rol'])) {

            $id_rol = $_POST['id_rol'];
            $emails = $_POST['emails'];

            $usuario = new Usuario();
            $resp = $usuario->guardarRoles($id_rol, $emails);
            echo json_encode($resp);
            //echo $resp;
        }
    }

    public function actualizarImagenGrupo()
    {

        $nombre = $_POST['nombre_grupo'] . ".png";
        $ruta = $_FILES['imagen_grupo']['tmp_name'];
        
        if (!file_exists('assets/img')) {
            mkdir('assets/img', 0777, true);
            if (!file_exists('assets/img')) {
                if (!move_uploaded_file($ruta, 'assets/img/' . $nombre)) {
                    $resultado['estado'] = "error_subir";
                    echo json_encode($resultado);
                    return false;
                }
            }
        } else {
            if (!move_uploaded_file($ruta, 'assets/img/' . $nombre)) {
                $resultado['estado'] = "error_subir";
                echo json_encode($resultado);
                return false;
            }
        }

        $resultado['estado'] = "exito";
        echo json_encode($resultado);
    }

    public function actualizarImagenTablero()
    {

        $nombre = $_POST['nombre_tablero'] . ".png";
        $ruta = $_FILES['imagen_tablero']['tmp_name'];
        
        if (!file_exists('assets/img')) {
            mkdir('assets/img', 0777, true);
            if (!file_exists('assets/img')) {
                if (!move_uploaded_file($ruta, 'assets/img/' . $nombre)) {
                    $resultado['estado'] = "error_subir";
                    echo json_encode($resultado);
                    return false;
                }
            }
        } else {
            if (!move_uploaded_file($ruta, 'assets/img/' . $nombre)) {
                $resultado['estado'] = "error_subir";
                echo json_encode($resultado);
                return false;
            }
        }

        $resultado['estado'] = "exito";
        echo json_encode($resultado);
    }

    public function obtenerDatosTablero(){
        if (isset($_POST['id_tablero'])) {

            $id_tablero = $_POST['id_tablero'];

            $tablero = new Tablero();
            $resp = $tablero->getById($id_tablero);

            echo json_encode($resp);
        }
    }

    public function actualizarTablero()
    {
        if (isset($_POST['form2_fecha_publicacion'])) {

            $form2_id_tablero = $_POST['form2_id_tablero'];
            $form2_fecha_publicacion = $_POST['form2_fecha_publicacion'];
            $form2_desc_tablero = $_POST['form2_desc_tablero'];
            $form2_comercial_responsable = $_POST['form2_comercial_responsable'];
            $form2_lider_responsable = $_POST['form2_lider_responsable'];
            $form2_nombre_pila_cliente = $_POST['form2_nombre_pila_cliente'];
            $form2_actualizacion_automatica = $_POST['form2_actualizacion_automatica'];

            $tablero = new Tablero();
            $resp = $tablero->actualizarTablero($form2_id_tablero, $form2_fecha_publicacion, $form2_desc_tablero, $form2_comercial_responsable, $form2_lider_responsable, $form2_nombre_pila_cliente, $form2_actualizacion_automatica);
            echo json_encode($resp);
        }
    }

    public function pruebaReceive()
    {   

        if (isset($_POST['jsones'])) {
            $vari = $_POST['jsones'];
            $taber = new Tablero();
            $taberForId = $taber->getAllByGroup($vari);
            //header("Content-type: application/json; charset=utf-8");
            echo json_encode($taberForId);
        }
        
        
    }

    public function pruebaReceive2()
    {   

        if (isset($_POST['idTab'])) {
            $vari = $_POST['idTab'];
            $taberuser = new Usuario();
            $taberForId = $taberuser->getAllUsersWithTab($vari);
            //header("Content-type: application/json; charset=utf-8");
            echo json_encode($taberForId);
        }
        
        
    }

    public function eliminarTabAccess(){

        $IdGroup = $_POST['idGroup'];
        $TabId = $_POST['idTab'];
        $emails = $_POST['email'];

        $dataResult = new Usuario();
        //$resp = $dataResult->guardarAccesos($IdGroup, $emails);
        $result = $dataResult->eliminarAccesosTab($emails,$TabId);

        echo json_encode($result);
        
    }

    public function agregarTabAccess(){

        $IdGroup = $_POST['idGroup'];
        $TabId = $_POST['idTab'];
        $emails = $_POST['email'];

        $dataResult = new Usuario();
        //$valid = $dataResult->getAllForTabValid($IdGroup,$emails);
        //echo json_encode($valid);
        //if($valid == true){
        //    echo json_encode($valid);
        //}
        //else{

        //echo json_encode($valid);
        $resp = $dataResult->guardarAccesos($IdGroup, $emails);
        $result = $dataResult->agregarAccesosTab($emails,$TabId);

        echo json_encode($resp);
        //}
        
    }


    public function vermasAccessos(){
        $tabers = new Usuario();
        if ($tabers->verificarSession()) {
        $tabersos = $tabers->getAllForTabAcc();
        //echo json_encode($tabersos[1],true);
        
        $this->view("tab", array(
                'tabersos' => $tabersos
        ));
        }
    }


    public function pruebas()
    {
        $usuario = new Usuario();
        $usuario->registrarUsuario("jruiz133@estudiantes.areandina.edu.co", "Juan Sebastian Ruiz Castañeda");
    }
}
