<?php
class ControladorBase
{

    public function __construct()
    {
        require_once 'Conectar.php';
        require_once 'EntidadBase.php';
        require_once 'ModeloBase.php';

        //Incluir todos los modelos
        foreach (glob("model/*.php") as $file) {
            require_once $file;
        }

        //Incluir el controlador para los menús
        require_once 'controller/menuController.php';
    }

    //Plugins y funcionalidades

    public function view($vista, $datos = NULL, $header = 'header')
    {

        try {

            if ($datos != NULL) {
                foreach ($datos as $id_assoc => $valor) {
                    ${$id_assoc} = $valor;
                }
            }

            require_once 'core/AyudaVistas.php';
            $helper = new AyudaVistas();

            //Si deseo un header diferente al general
            if($header != "no_header"){
                require_once 'view/' . $header . 'View.php';
            }
            require_once 'view/' . $vista . 'View.php';
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function redirect($controlador = CONTROLADOR_DEFECTO, $accion = ACCION_DEFECTO, $parametros = NULL)
    {
        if ($parametros == NULL) {
            header("Location:index.php?controller=" . $controlador . "&action=" . $accion);
        } else {
            $strParam = "";
            for ($i = 0; $i < count($parametros); $i++) {
                $strParam = $strParam . "&" . $parametros[$i][0] . "=" . $parametros[$i][1];
            }
            header("Location:index.php?controller=" . $controlador . "&action=" . $accion . $strParam);
        }
    }

}
